<?php namespace Shambou\RequestLogs;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Shambou\RequestLogs\Classes\Logging\RequestLogFactory;
use Shambou\RequestLogs\Classes\Parsing\RequestLogParserFactory;
use Shambou\RequestLogs\Console\InstallRequestLogs;
use Shambou\RequestLogs\Http\Middleware\RequestLogMiddleware;

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
        // Register a Facade
        $this->app->bind('requestlog_factory', function ($app) {
            return new RequestLogFactory();
        });

        $this->app->bind('requestlog_parser_factory', function ($app) {
            return new RequestLogParserFactory();
        });

        // Register a global middleware
        // $kernel = $this->app->make(Kernel::class);
        // $kernel->pushMiddleware(RequestLogMiddleware::class);
        
        // Register a route specific middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('requestlogs', RequestLogMiddleware::class);


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

            //			$this->publishes([
//				__DIR__.'/../resources/views' => resource_path('views/'),
//			], 'views');
//
//			$this->publishes([
//				__DIR__.'/../resources/assets' => public_path('requestlogs'),
//			], 'assets');
        }
    }
}
