<?php namespace Shambou\RequestLogs\Tests;

use Shambou\RequestLogs\RequestLogServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        include_once __DIR__ . '/../database/migrations/create_request_logs_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_request_log_relations_table.php.stub';

        // run the migration's up() method
        (new \CreateRequestLogRelationsTable)->up();
        (new \CreateRequestLogsTable)->up();
    }

    protected function getPackageProviders($app)
    {
        return [
            RequestLogServiceProvider::class,
        ];
    }
}
