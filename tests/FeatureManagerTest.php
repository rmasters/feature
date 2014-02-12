<?php

namespace Feature\Tests;

use PHPUnit_Framework_TestCase;
use Feature\FeatureManager;

class FeatureManagerTest extends PHPUnit_Framework_TestCase
{
    public function testAddFeature()
    {
        $features = new FeatureManager;

        $this->assertFalse($features->can('signup'));

        $features->enable('signup', function($user) {
            return false;
        });
        $this->assertFalse($features->can('signup'));

        $features->enable('signup', function($user) {
            return true;
        });
        $this->assertTrue($features->can('signup'));
    }
}
