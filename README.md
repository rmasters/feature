# feature/feature [![Build status](http://img.shields.io/travis/rmasters/feature.svg)](https://travis-ci.org/rmasters/feature) [![Coverage Status](https://coveralls.io/repos/rmasters/feature/badge.png)](https://coveralls.io/r/rmasters/feature)

A simple feature-flag API that can be used for toggling functionality.

## Usage

    $features = new FeatureManager;

    $features->setUser(new User(['id' => 1]));

    $features->enable('new_calendar', function(Feature\User $user) {
        return in_array($user->id, [1, 2, 5]);
    });

    if ($features->can('new_calendar')) {
        // ...
    }

Enable features by adding toggles that inspect attributes of the current user.
Toggles can be as simple as a closure or any instance that implements
[Toggle](src/Toggle.php).

## Storage

Feature toggles can be defined in three ways:

-   Direct PHP - as above,
-   a structured array,
-   or from a database.

Closures are supported by PHP and arrays in PHP (i.e. not from YAML etc.) Files
and database require the toggle definitions to be pre-defined.

### Structured array

    $featureToggles = [
        // Feature name
        'view_calendar' => [
            // Each toggle with it's class name and constructor arguments
            ['name' => 'Feature\Toggles\IPWhitelist', 'params' => ['127.0.0.1']],

            // or a closure
            function(Feature\User $user) {
                return $user->id % 3 == 0;
            },

            // or a Toggle instance
            new Feature\Toggles\IPWhitelist(['192.168.0.1', '192.168.0.2']),
        ]
    ];

    $features = new FeatureManager;
    $storage = new Feature\Storage\Structured($features, $featureToggles);
    $storage->load();
