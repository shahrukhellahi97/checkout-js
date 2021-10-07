<?php
namespace Deployer;
use Deployer\Task\Context;

require 'recipe/common.php';

inventory(__DIR__ . '/hosts.yml');

host('staging.brandadditionweb.com')
    ->set('deploy_path', '/var/www/vhosts/brandaddition/clients/' . $_SERVER['DEPLOY_CLIENT']);

host('brandadditionweb.com')
    ->set('deploy_path', '/var/www/vhosts/brandaddition/clients/' . $_SERVER['DEPLOY_CLIENT']);

// 
// Deployer Settings
//---------------------------------
set('application', 'Magento');
set('static_content_locales', 'en_US');
set('shared_files', [
    'app/etc/env.php',
    'var/.maintenance.ip',
]);

set('shared_dirs', [
    'var/composer_home',
    'var/log',
    'var/cache',
    'var/export',
    'var/report',
    'var/import_history',
    'var/session',
    'var/importexport',
    'var/backups',
    'var/tmp',
    'pub/sitemaps',
    'pub/media'
]);

set('writable_dirs', [
    'var',
    'pub/static',
    'pub/media',
    'generated'
]);

set('clear_paths', [
    'generated/*',
    'pub/static/_cache/*',
    'var/generation/*',
    'var/cache/*',
    'var/page_cache/*',
    'var/view_preprocessed/*'
]);

//
// Helpers
//--------------------------

function is_magento_installed() {
    return test('[ -f {{release_path}}/app/etc/env.php ]') &&
        run('if cat {{release_path}}/app/etc/env.php | grep "\'date\' =>" > /tmp/test.txt; then echo 1; else echo 0; fi') === '1';
}
//
// Tasks
//--------------------------
// Pack Magento files from repository
desc('Magento Pack');
task('magento:pack', function(){ 
    run('tar --exclude=env.php -cvzf archive.tar.gz src/app src/composer.lock src/composer.json');
})->local();

// Upload code to server
desc('Magento Upload Code');
task('magento:upload', function(){ 
    upload(__DIR__ . "/archive.tar.gz", '{{release_path}}/archive.tar.gz');
});

// Unpack code on server
desc('Magento Unpack');
task('magento:unpack', function(){ 
    run('cd {{release_path}} && tar --strip-components=1 -xzf archive.tar.gz src');
    run('rm {{release_path}}/archive.tar.gz');
});

// Configure composer
desc('Composer - Configure');
task('composer:configure', function(){
    run("sudo -u magento -- {{bin/composer}} config --global github-oauth.github.com $_SERVER[COMPOSER_GITHUB_OAUTH_TOKEN]");
    run("sudo -u magento -- {{bin/composer}} config --global bitbucket-oauth.bitbucket.org $_SERVER[COMPOSER_BITBUCKET_OAUTH_KEY] $_SERVER[COMPOSER_BITBUCKET_OAUTH_SECRET]");
    run("sudo -u magento -- {{bin/composer}} config --global http-basic.repo.magento.com $_SERVER[COMPOSER_MAGENTO_USERNAME] $_SERVER[COMPOSER_MAGENTO_PASSWORD]");
    run("sudo -u magento -- {{bin/composer}} config --global http-basic.dev.azure.com $_SERVER[COMPOSER_AZURE_USERNAME] $_SERVER[COMPOSER_AZURE_PASSWORD]");
    run("sudo -u magento -- {{bin/composer}} config --global process-timeout 2000");
});

desc('Composer - Install');
task('composer:install', function(){
    run('cd {{release_path}} && sudo -u magento -- {{bin/composer}} install');
});

desc('Server - Set Permissions');
task('magento:set-permissions-shared', function(){
    $deploy = get('deploy_path');

    run("cd $deploy/shared && \
        find var pub/media app/etc -type f -exec chmod g+w {} + && \
        find var pub/media app/etc -type d -exec chmod g+ws {} +
    ");

    run("chown -R magento:magento $deploy/shared");
    run("chown -R magento:magento {{release_path}}");
    run("chmod 770 $deploy/shared/pub");
    run("chmod 770 $deploy/shared/var");
});

desc('Magento - Install');
task('magento:install', function() {
    if (!is_magento_installed()) {

        // Setup rabbit
        run("rabbitmqctl add_vhost /$_SERVER[DEPLOY_CLIENT]");
        run("rabbitmqctl set_permissions -p /$_SERVER[DEPLOY_CLIENT] magento  \".*\" \".*\" \".\"");

        run("echo '<?php return [];' > {{release_path}}/app/etc/env.php");
        // run("rm {{release_path}}/app/etc/env.php");
        run('echo x > {{release_path}}/fresh-install.txt');

        $hostname = Context::get()->getHost()->getHostname();
        $install = "sudo -u magento -- {{bin/php}} {{release_path}}/bin/magento setup:install \
            --db-host=\"$_SERVER[DEPLOY_MYSQL_HOSTNAME]\" \
            --db-name=\"$_SERVER[DEPLOY_MYSQL_DATABASE]\" \
            --db-user=\"$_SERVER[DEPLOY_MYSQL_USERNAME]\" \
            --db-password=\"$_SERVER[DEPLOY_MYSQL_PASSWORD]\" \
            --base-url=\"http://$_SERVER[DEPLOY_HOSTNAME]\" \
            --base-url-secure=\"https://$_SERVER[DEPLOY_HOSTNAME]\" \
            --admin-firstname=\"BA\" \
            --admin-lastname=\"Admin\" \
            --admin-email=\"websupport@brandaddition.com\" \
            --admin-user=\"$_SERVER[DEPLOY_MAGENTO_USERNAME]\" \
            --admin-password=\"$_SERVER[DEPLOY_MAGENTO_PASSWORD]\" \
            --backend-frontname=\"$_SERVER[DEPLOY_MAGENTO_ADMIN_FRONTNAME]\" \
            --elasticsearch-host=10.1.0.4\
            --elasticsearch-port=9200\
            --elasticsearch-index-prefix=$_SERVER[DEPLOY_CLIENT]\
            --session-save=redis\
            --session-save-redis-host=10.1.0.4\
            --session-save-redis-db=2\
            --cache-backend=redis\
            --cache-backend-redis-db=0\
            --cache-backend-redis-server=10.1.0.4\
            --page-cache=redis\
            --page-cache-redis-db=1\
            --page-cache-redis-server=10.1.0.4\
            --lock-provider=zookeeper\
            --lock-zookeeper-host=localhost:2181\
            --lock-zookeeper-path=/var/www/vhosts/zookeeper\
            --amqp-host=bal-webdb-01\
            --amqp-port=5672\
            --amqp-user=magento\
            --amqp-password=Black2Black\
            --amqp-virtualhost=\"/$_SERVER[DEPLOY_CLIENT]\"\
            --use-rewrites=1 \
            --use-secure=1 \
            --use-secure-admin=1 \
            --currency=GBP \
            --timezone=Europe/London \
            --cleanup-database
        ";

        run($install);
    }
});

desc('Magento - Compile');
task('magento:compile', function() {
    run("redis-cli -h $_SERVER[DEPLOY_REDIS_HOSTNAME] -n 0 flushdb");
    run("redis-cli -h $_SERVER[DEPLOY_REDIS_HOSTNAME] -n 1 flushdb");
    run("chmod u+x {{release_path}}/bin/magento");
    // run("sudo -u magento -- {{bin/php}} {{release_path}}/bin/magento cache:clean");
    // run("sudo -u magento -- {{bin/php}} {{release_path}}/bin/magento cache:flush");
    // run("sudo -u magento -- {{bin/php}} {{release_path}}/bin/magento app:config:import -n");
    run("sudo -u magento -- {{bin/php}} {{release_path}}/bin/magento setup:di:compile");
});

desc('Magento - Upgrade');
task('magento:upgrade', function() {
    if (!test('[ -f {{release_path}}/fresh-install.txt ]')) {
        run("sudo -u magento -- {{bin/php}} {{release_path}}/bin/magento setup:upgrade --keep-generated");
     //   run("kill $(ps aux | grep '" . $_SERVER['DEPLOY_CLIENT'] . ".*bin/magento queue:consumers:start' | awk '{print $2}')");
       // run("sudo -u magento -- {{bin/php}} {{release_path}}/bin/magento queue:consumers:start basys.command.process --single-thread --max-messages=10000");
    }
});
desc('Magento - maintenance mode - enable');
task('magento:maintenance:enable', function () {
    run("if [ -d $(echo {{deploy_path}}/current) ]; then {{bin/php}} {{deploy_path}}/current/bin/magento maintenance:enable; fi");
});

desc('Magento - maintenance mode - disable');
task('magento:maintenance:disable', function () {
    run("if [ -d $(echo {{deploy_path}}/current) ]; then {{bin/php}} {{deploy_path}}/current/bin/magento maintenance:disable; fi");
});

desc('Magento - Deploy Assets');
task('magento:assets', function () {
    if (is_magento_installed()) {
        run("sudo -u magento -- {{bin/php}} {{release_path}}/bin/magento setup:static-content:deploy -f {{static_content_locales}}");
    }
});

desc('Magento - Flush Cache');
task('magento:flush', function () {
    if (is_magento_installed()) {
        run("{{bin/php}} {{release_path}}/bin/magento cache:clean");
        run("{{bin/php}} {{release_path}}/bin/magento cache:flush");
        run("{{bin/php}} -r 'opcache_reset();'");
    }
});

// Add multiplexing to CircleCI
desc('Add Multiplexing');
task('circleci:enable-multiplexing', function(){
    $conf = <<<EOF
host *
    controlmaster auto
    controlpath /tmp/ssh-%r@%h:%p
EOF;
    
    run("mkdir -p ~/.ssh && touch ~/.ssh/config && echo '{$conf}' > ~/.ssh/config");
    run('chmod 600 ~/.ssh/config');

})->local();

desc('Fix Permissions');
task('fix-permissions', function(){


    run("cd {{release_path}} && sudo -u magento -- find var generated vendor pub/static pub/media app/etc -type f -exec chmod g+w {} +");
    run("cd {{release_path}} && sudo -u magento -- find var generated vendor pub/static pub/media app/etc -type d -exec chmod g+ws {} +");
    run("cd {{release_path}} && chmod -R 770 var");

    $deploy = get('deploy_path');

    run("chmod -R 770 $deploy/shared");
    run("chown -R magento:magento {{release_path}}");
});

task('restart-services', function(){
    run("pcs resource restart r_web_php-fpm");
});

//
// Task Composites
//-----------------------
desc('Update Magento');
task('magento:update', [
    'magento:pack',
    'magento:upload',
    'magento:unpack',
]);


desc('Install/Upgrade Magento');
task('magento:install-upgrade', [
    'composer:configure',
    'composer:install',
    'magento:install',
    'magento:compile',
    'magento:assets',
    'magento:maintenance:enable',
    'magento:upgrade',
    'magento:flush',
    'fix-permissions',
    'magento:maintenance:disable'
]);

// Deploy the project
desc('Deploy');
task('deploy', [
    'circleci:enable-multiplexing',
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'magento:update',
    'deploy:shared',
    'deploy:writable',
    'magento:set-permissions-shared',
    'deploy:clear_paths',
    'magento:install-upgrade',
    'deploy:symlink',
    'restart-services',
    'deploy:unlock',
    'cleanup',
    'success'
]);