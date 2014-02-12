<?php

namespace Feature\Tests\Toggles;

use PHPUnit_Framework_TestCase;
use Feature\User;
use Feature\Toggles\IPWhitelist;
use Mockery as m;

class IPWhitelistTest extends PHPUnit_Framework_TestCase
{
    public function testWhitelist()
    {
        $toggle = new IPWhitelist(['127.0.0.1', '192.168.0.1']);

        $user = new User;

        $user->set('remote_addr', '127.0.0.1');
        $this->assertTrue($toggle($user));

        $user->set('remote_addr', '8.8.8.8');
        $this->assertFalse($toggle($user));
    }
}
