<?php
$levels = 20;

for ($i = $levels; $i >= 0; $i--) {
    $topic = implode('.', 
        array_merge(
            array_fill(0, $levels - $i, '*'),
            ['1', '#']
        )
    );
    $nextDelay = $i - 1;

// echo 
// <<<HTML

//     <publisher topic="{$topic}">
//         <connection name="amqp" exchange="basys.command.delay-{$nextDelay}" />
//     </publisher>

// HTML;
echo 
<<<HTML

    <exchange name="basys.command.delay-{$i}" connection="amqp" durable="true" type="topic">
        <binding id="delay-{$i}" topic="{$topic}" destination="queue" destinationType="queue">
            <arguments>
                <argument name="x-dead-letter-exchange" xsi:type="string">basys.command.delay-{$nextDelay}</argument>
                <argument name="x-message-ttl" xsi:type="number">60000</argument>
            </arguments>
        </binding>
    </exchange>

HTML;
}