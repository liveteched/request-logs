<?php namespace Shambou\RequestLogs\Console;

use Illuminate\Console\Command;

class InstallRequestLogs extends Command
{
    protected $signature = 'requestlogs:install';

    protected $description = 'Install the RequestLogs';

    public function handle()
    {
        $this->info('Installing RequestLogs...');

        $this->info('Publishing configuration...');

        $this->call('vendor:publish', ['--provider' => "Shambou\RequestLogs\RequestLogServiceProvider"]);

        $this->info('Installed RequestLogs package');
    }
}
