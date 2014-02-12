<?php

namespace Feature\Laravel;

use Illuminate\Support\ServiceProvider;
use Feature\FeatureManager;
use Feature\User;

class FeatureServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $namespace = 'feature';

        $this->package('feature/feature', $namespace);
        $this->app['config']->addNamespace($namespace, __DIR__ . '/config.php');
    }

    public function register()
    {
        $features = new FeatureManager;

        // Add in the currently authenticated user
        $userAttr = $this->app['config']->get('feature.storage.user_attr', 'user');

        $user = new User([$userAttr => $this->app['auth']->user()]);
        $user->loadFromServer($this->app['request']->server());

        $features->setUser($user);

        // Load features from storage
        $this->configure($features);

        $this->app->instance('feature', $features);
    }

    protected function configure(FeatureManager $manager)
    {
        $config = $this->app['config']->get('feature.storage', []);

        // Instantiate the storage loader
        if (isset($config['loader'])) {
            $reflection = new ReflectionClass($config['loader']);

            // Build arguments and construct
            $args = array_merge(
                [$manager],
                isset($config['options']) ? $config['options'] : []
            );
            $storage = $reflection->newInstanceArgs($args);

            $storage->load();
        }
    }

    public function provides()
    {
        return ['feature'];
    }
}
