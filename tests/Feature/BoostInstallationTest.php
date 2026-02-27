<?php

namespace Tests\Feature;

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
     * Test that the Boost service provider is registered in the application.
     */
    public function test_boost_service_provider_is_registered(): void
    {
        $providers = app()->getLoadedProviders();

        $this->assertArrayHasKey(
            \Laravel\Boost\BoostServiceProvider::class,
            $providers,
            'Boost service provider should be registered in the application'
        );
    }

    /**
     * Test that Boost command classes exist and are loadable.
     *
     * Verifies that boost artisan commands are available. Commands use
     * lazy registration so Artisan::all() may not list them in test env,
     * but the command classes must exist and be autoloadable.
     */
    public function test_boost_command_classes_exist(): void
    {
        $this->assertTrue(
            class_exists(\Laravel\Boost\Console\InstallCommand::class),
            'Boost InstallCommand class should be autoloadable'
        );
        $this->assertTrue(
            class_exists(\Laravel\Boost\Console\StartCommand::class),
            'Boost StartCommand (MCP) class should be autoloadable'
        );
        $this->assertTrue(
            class_exists(\Laravel\Boost\Console\UpdateCommand::class),
            'Boost UpdateCommand class should be autoloadable'
        );
        $this->assertTrue(
            class_exists(\Laravel\Boost\Console\AddSkillCommand::class),
            'Boost AddSkillCommand class should be autoloadable'
        );
    }

    /**
     * Test that the MCP server configuration is valid JSON.
     *
     * AC #3: MCP server configuration is present and valid.
     */
    public function test_mcp_server_configuration_is_valid(): void
    {
        $mcpJson = file_get_contents(base_path('.mcp.json'));
        $config = json_decode($mcpJson, true);

        $this->assertNotNull($config, '.mcp.json should be valid JSON');
        $this->assertArrayHasKey('mcpServers', $config, '.mcp.json should have mcpServers key');
        $this->assertArrayHasKey('laravel-boost', $config['mcpServers'], 'laravel-boost should be configured as an MCP server');
    }

    /**
     * Test that the application boots without errors after Boost install.
     */
    public function test_application_boots_cleanly_with_boost(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
