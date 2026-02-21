<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class BoostInstallationTest extends TestCase
{
    /**
     * Test that Boost configuration files exist.
     */
    public function test_boost_config_files_exist(): void
    {
        $this->assertFileExists(base_path('.mcp.json'));
        $this->assertFileExists(base_path('boost.json'));
    }

    /**
     * Test that Boost Artisan commands classes exist.
     */
    public function test_boost_artisan_commands_are_registered(): void
    {
        $this->assertTrue(class_exists(\Laravel\Boost\Console\InstallCommand::class));
        $this->assertTrue(class_exists(\Laravel\Boost\Console\StartCommand::class));
        $this->assertTrue(class_exists(\Laravel\Boost\Console\UpdateCommand::class));
        $this->assertTrue(class_exists(\Laravel\Boost\Console\AddSkillCommand::class));
    }

    /**
     * Test that the application boots without errors.
     */
    public function test_application_boots_cleanly(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
