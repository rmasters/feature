# feature/feature

A simple feature-flag API that can be used for toggling functionality.

## Usage

    $features = new FeatureManager;

    $features->setUser(['id' => 123]);

    $features->enable('new_calendar', function(Feature\User $user) {
        return in_array($user->id, [1, 2, 5]);
    });

    if ($features->can('new_calendar')) {
        // ...
    }

Enable features by adding toggles that inspect attributes of the current user.
Toggles can be as simple as a closure or any instance that implements `__invoke`.
See [Toggle.php](src/Toggle.php) for an interface to implement.
