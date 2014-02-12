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
        // Mock User and Auth
        $user = m::mock('Illuminate\Auth\UserInterface');
        $auth = m::mock('Illuminate\Auth\AuthServiceProvider');
        $auth->shouldReceive('user')->andReturn($user);

        // Mock Config
        $config = m::mock('Illuminate\Config\Repository');
        $config->shouldReceive('addNamespace')->with('feature', m::any());
        // Use the default values
        $config->shouldReceive('get')->withAnyArgs()->andReturn(null);
        $config->shouldReceive('has')->withAnyArgs()->andReturn(false);

        // Mock Request
        $request = m::mock('Illuminate\Http\Request');
        $request->shouldReceive('server')->withNoArgs()->andReturn([]);

        // Mock Application
        $app = m::mock('Illuminate\Foundation\Application');
        $app->shouldReceive('offsetGet')->with('auth')->andReturn($auth);
        $app->shouldReceive('offsetGet')->with('config')->andReturn($config);
        $app->shouldReceive('offsetGet')->with('request')->andReturn($request);

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
