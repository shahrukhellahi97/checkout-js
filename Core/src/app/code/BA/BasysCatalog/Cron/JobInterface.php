<?php
namespace BA\BasysCatalog\Cron;

interface JobInterface
{
    /**
     * Execute cron job
     * 
     * @return void
     */
    public function execute();
}