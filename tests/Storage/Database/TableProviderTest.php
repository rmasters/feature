<?php

namespace Feature\Tests\Storage\Database;

use PHPUnit_Framework_TestCase;

class TableProviderTest extends PHPUnit_Framework_TestCase
{
    protected $tables;

    public function setUp()
    {
        $this->tables = new StubTableProvider;
    }

    public function testGetFeatureToggles()
    {
        $features = $this->tables->getFeatureToggles();

        $this->assertCount(1, $features);
        $this->assertArrayHasKey('beta_theme', $features);

        $this->assertCount(2, $features['beta_theme']);

        // First toggle should have an array of IPs
        $toggle = $features['beta_theme'][0];
        $this->assertInternalType('array', $toggle);
        // Name
        $this->assertArrayHasKey('name', $toggle);
        $this->assertEquals('Feature\Toggles\IPWhitelist', $toggle['name']);
        // Params
        $this->assertArrayHasKey('params', $toggle);
        $this->assertInternalType('array', $toggle['params']);
        $this->assertCount(1, $toggle['params'], 'Toggle should have 1 argument');
        $this->assertInternalType('array', $toggle['params'][0]);
        $this->assertCount(3, $toggle['params'][0], 'Arg 0 should be a list of 3 IPs');
        $this->assertContains('127.0.0.1', $toggle['params'][0]);

        // Second toggle should have a single IP (string)
        $toggle = $features['beta_theme'][1];
        $this->assertInternalType('array', $toggle);
        // Params
        $this->assertArrayHasKey('params', $toggle);
        $this->assertInternalType('array', $toggle['params']);
        $this->assertCount(1, $toggle['params']);
        $this->assertInternalType('string', $toggle['params'][0]);
        $this->assertEquals('127.0.0.1', $toggle['params'][0]);
    }
}
