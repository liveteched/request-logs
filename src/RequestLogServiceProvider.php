<?php namespace Shambou\RequestLogs;

use Illuminate\Support\ServiceProvider;
use Shambou\RequestLogs\Classes\Logging\RequestLogFactory;
use Shambou\RequestLogs\Classes\Parsing\RequestLogParserFactory;
use Shambou\RequestLogs\Console\InstallRequestLogs;

/**
* @author Sasa Milasinovic
*/
class RequestLogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'requestlogs');
    }

    public function boot()
    {
        // Register Facades
        $this->app->bind('requestlog_factory', function ($app) {
            return new RequestLogFactory();
        });

        $this->app->bind('requestlog_parser_factory', function ($app) {
            return new RequestLogParserFactory();
        });


        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('requestlogs.php'),
            ], 'config');

            $this->commands([
                InstallRequestLogs::class,
            ]);

            if (! class_exists('CreateRequestLogsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_request_logs_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', strtotime('-1 minute')) . '_create_request_logs_table.php'),
                    __DIR__ . '/../database/migrations/create_request_log_relations_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_request_log_relations_table.php'),
                    // you can add any number of migrations here
                ], 'migrations');
            }
        }
    }
}
