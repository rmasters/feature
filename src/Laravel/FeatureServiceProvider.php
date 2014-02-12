<?php

namespace Feature\Laravel;

use Illuminate\Support\ServiceProvider;
use Feature\FeatureManager;

class FeatureServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->package('feature/feature', 'feature');
    }

    public function register()
    {
        $features = new FeatureManager;

        // Add in the currently authenticated user
        $features->setUser($this->app['auth']->user());

        $this->app->instance('feature', $features);
    }

    public function provides()
    {
        return ['feature'];
    }
}
