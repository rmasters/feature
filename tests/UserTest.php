<?php

namespace Feature\Tests;

use PHPUnit_Framework_TestCase;
use DateTime;
use Feature\User;

class UserTest extends PHPUnit_Framework_TestCase
{
    private $user;

    public function setUp()
    {
        $this->user = new User([
            'id' => 1,
            'name' => 'Ross',
        ]);
    }

    public function testMethods()
    {
        $this->assertTrue($this->user->has('id'));
        $this->assertFalse($this->user->has('email'));

        $this->assertEquals(1, $this->user->get('id'));
        $this->assertNull($this->user->get('email'));

        $this->user->set('id', 2);
        $this->assertEquals(2, $this->user->get('id'));

        $this->user->forget('id');
        $this->assertFalse($this->user->has('id'));
    }

    public function testAsObject()
    {
        $this->assertTrue(isset($this->user->id));
        $this->assertFalse(isset($this->user->email));

        $this->assertEquals(1, $this->user->id);
        $this->assertNull($this->user->email);

        $this->user->id = 2;
        $this->assertEquals(2, $this->user->id);

        unset($this->user->id);
        $this->assertFalse(isset($this->user->id));
        $this->assertNull($this->user->id);
    }

    public function testAsArray()
    {
        $this->assertTrue(isset($this->user['id']));
        $this->assertFalse(isset($this->user['email']));

        $this->assertEquals(1, $this->user['id']);
        $this->assertNull($this->user['email']);

        $this->user['id'] = 2;
        $this->assertEquals(2, $this->user['id']);

        unset($this->user['id']);
        $this->assertFalse(isset($this->user['id']));
        $this->assertNull($this->user['id']);
    }

    public function testToArray()
    {
        $this->assertEquals(
            ['id' => 1, 'name' => 'Ross'],
            $this->user->toArray()
        );
    }

    public function testFromServer()
    {
        $this->user->loadFromServer([
            'REMOTE_ADDR' => '127.0.0.1',
            'REMOTE_HOST' => 'localhost',
        ]);

        $this->assertTrue($this->user->has(User::REMOTE_ADDR));
        $this->assertTrue($this->user->has(User::REMOTE_NAME));
        $this->assertEquals('localhost', $this->user->get(USER::REMOTE_NAME));
    }

    public function testFromServerSuperglobal()
    {
        $oldServer = $_SERVER;
        $_SERVER = ['REMOTE_ADDR' => '127.0.0.1'];

        $user = new User;
        $user->loadFromServer();

        $this->assertTrue($user->has(User::REMOTE_ADDR));
        $this->assertEquals('127.0.0.1', $user->get(User::REMOTE_ADDR));

        $_SERVER = $oldServer;
    }
}
