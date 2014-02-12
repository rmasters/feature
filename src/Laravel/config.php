<?php

return array(
    'storage' => [
        /**
         * Select storage loader to use:
         *  - Feature\Storage\Structured for array-based configuration,
         *  - or configure using the FeatureManager instance directly.
         */
        'loader' => 'Feature\Storage\Structured',
        'options' => [
            [
                /* Add a feature named 'beta' with 2 toggles
                 * Matching any of the toggles will enable the feature
                'beta' => [
                    ['name' => 'Feature\Toggles\IPWhitelist',
                     'params' => ['127.0.0.1', '10.0.0.2']],

                    function(Feature\User $user) {
                        return $user->user_host == 'beta.example.com';
                    },
                ],
                 */
            ],
        ],
    ],

    /**
     * The attribute the Auth::user() is stored under in the User object passed to toggles
     */
    'user_attr' => 'user',
);
