<?php

namespace Feature\Tests\Laravel;

use PHPUnit_Framework_TestCase;
use Feature\Laravel\FeatureServiceProvider;
use Mockery as m;

class ServiceProviderTest extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $app = $this->getApplication();

        $app->register(new FeatureServiceProvider($app));

        $this->assertInstanceOf('Feature\FeatureManager', $app['feature']);
    }

    private function getApplication()
    {
        $user = m::mock('Illuminate\Auth\UserInterface');

        $auth = m::mock('Illuminate\Auth\AuthServiceProvider');
        $auth->shouldReceive('user')->andReturn($user);

        $app = m::mock('Illuminate\Foundation\Application');
        $app->shouldReceive('offsetGet')->with('auth')->andReturn($auth);

        $app->shouldReceive('register')->with(m::type('Feature\Laravel\FeatureServiceProvider'))
            ->andReturnUsing(function($provider) {
                $provider->register();
            });

        // Copy the feature manager passed to instance() so we can return it later
        $manager = null;
        $app->shouldReceive('instance')->with('feature', m::type('Feature\FeatureManager'))
            ->andReturnUsing(function($name, $instance) use (&$manager) {
                $manager = $instance;
            });

        $app->shouldReceive('offsetGet')->with('feature')
            ->andReturnUsing(function() use (&$manager) {
                return $manager;
            });

        return $app;
    }
}
