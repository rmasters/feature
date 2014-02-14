<?php

namespace Feature\Tests\Storage;

use PHPUnit_Framework_TestCase;
use Mockery as m;
use Feature\Storage\Database;

class DatabaseTest extends PHPUnit_Framework_TestCase
{
    public function testCallsTablesCorrectly()
    {
        $tables = m::mock('Feature\Storage\Database\TableProvider');
        $tables->shouldReceive('getFeatureToggles')->withNoArgs()->andReturn([]);

        $manager = m::mock('Feature\FeatureManager');

        $storage = new Database($manager, $tables);
        $storage->load();
    }
}
