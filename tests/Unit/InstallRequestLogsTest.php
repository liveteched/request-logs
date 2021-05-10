<?php namespace Shambou\RequestLogs\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Shambou\RequestLogs\Tests\TestCase;

class InstallRequestLogsTest extends TestCase
{
    /** @test */
    function the_install_command_copies_a_the_configuration()
    {
        // make sure we're starting from a clean state
        if (File::exists(config_path('requestlogs.php'))) {
            unlink(config_path('requestlogs.php'));
        }

        $this->assertFalse(File::exists(config_path('requestlogs.php')));

        Artisan::call('requestlogs:install');

        $this->assertTrue(File::exists(config_path('requestlogs.php')));
    }
}
