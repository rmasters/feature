<?php

namespace Feature\Tests\Storage\Database;

use Feature\Storage\Database\TableProvider;

class StubTableProvider extends TableProvider
{
    public function getToggles()
    {
        return [
            [
                'id' => 1,
                'feature' => 'beta_theme',
                'toggle' => 'Feature\Toggles\IPWhitelist',
            ],
            [
                'id' => 2,
                'feature' => 'beta_theme',
                'toggle' => 'Feature\Toggles\IPWhitelist',
            ],
        ];
    }

    public function getToggleArguments()
    {
        return [
            [
                'toggle_id' => 1,
                'position' => 0,
                'value' => '127.0.0.1',
            ],
            [
                'toggle_id' => 1,
                'position' => 0,
                'value' => '192.168.0.1',
            ],
            [
                'toggle_id' => 1,
                'position' => 0,
                'value' => '192.168.0.2',
            ],
            [
                'toggle_id' => 2,
                'position' => 0,
                'value' => '127.0.0.1',
            ],
        ];
    }
}
