<?php

namespace Feature\Tests\Storage;

use PHPUnit_Framework_TestCase;
use Feature\FeatureManager;
use Feature\User;
use Feature\Storage\Structured;
use Feature\Toggles\IPWhitelist;

class StructuredTest extends PHPUnit_Framework_TestCase
{
    protected $manager;

    public function setUp()
    {
        $this->manager = new FeatureManager;
    }

    public function testValid()
    {
        $options = [
            'view_calendar' => [
                function (User $user) {
                    return $user->id == 1;
                },
                ['name' => 'Feature\Toggles\IPWhitelist',
                 'params' => ['127.0.0.1', '127.0.0.2']],
            ],
            'revamped_stats' => [
                new IPWhitelist(['127.0.0.1']),
            ],
        ];

        $storage = new Structured($this->manager, $options);
        $storage->load();

        $user1 = new User(['id' => 1]);
        $user2 = new User([User::REMOTE_ADDR => '127.0.0.1']);

        $this->manager->setUser($user1);
        $this->assertTrue($this->manager->can('view_calendar'));
        $this->assertFalse($this->manager->can('revamped_stats'));

        $this->manager->setUser($user2);
        $this->assertTrue($this->manager->can('view_calendar'));
        $this->assertTrue($this->manager->can('revamped_stats'));
    }

    public function testInvalidToggle()
    {
        $this->setExpectedException('\Exception', 'Toggles must be an array or callable');

        $storage = new Structured($this->manager, ['view_calendar' => [5]]);
        $storage->load();
    }

    public function testInvalidToggleDefinition()
    {
        $this->setExpectedException('\Exception', 'Toggle configuration must have a toggle name');

        $storage = new Structured($this->manager, ['view_calendar' => ['params' => [123]]]);
        $storage->load();
    }
}
