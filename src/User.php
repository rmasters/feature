<?php

namespace Feature;

use ArrayAccess;

/**
 * Container for user information
 */
class User implements ArrayAccess
{
    protected $attributes;

    const REMOTE_ADDR = 'remote_addr';
    const REMOTE_NAME = 'remote_name';
    const HOST_ADDR = 'host_addr';
    const HOST_NAME = 'host_name';
    const QUERY_STRING = 'query_string';
    const USER_ACCEPT = 'user_accept';
    const USER_ACCEPT_CHARSET = 'user_accept_charset';
    const USER_ACCEPT_ENCODING = 'user_accept_encoding';
    const USER_ACCEPT_LANGUAGE = 'user_accept_language';
    const USER_HOST = 'user_host';
    const USER_REFERER = 'user_referer';
    const USER_AGENT = 'user_agent';

    /**
     * Create a new user information container
     * @param array|null $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Pre-load the User instance with information from $_SERVER
     * @param array|null $server Optional array to read from
     */
    public function loadFromServer($server = null)
    {
        $server = $server ?: $_SERVER;

        $get = function ($key, $default = null) use ($server) {
            return isset($server[$key]) ? $server[$key] : $default;
        };

        // Remote client IP/hostname
        $this->set(static::REMOTE_ADDR, $get('REMOTE_ADDR'));
        $this->set(static::REMOTE_NAME, $get('REMOTE_HOST'));

        // Server IP/hostname
        $this->set(static::HOST_ADDR, $get('SERVER_ADDR'));
        $this->set(static::HOST_NAME, $get('SERVER_NAME'));

        // Query string vars
        $qs = parse_str($get('QUERY_STRING'));
        $this->set(static::QUERY_STRING, $qs);

        // Client headers
        $this->set(static::USER_ACCEPT, $get('HTTP_ACCEPT'));
        $this->set(static::USER_ACCEPT_CHARSET, $get('HTTP_ACCEPT_CHARSET'));
        $this->set(static::USER_ACCEPT_ENCODING, $get('HTTP_ACCEPT_ENCODING'));
        $this->set(static::USER_ACCEPT_LANGUAGE, $get('HTTP_ACCEPT_LANGUAGE'));
        $this->set(static::USER_HOST, $get('HTTP_HOST'));
        $this->set(static::USER_REFERER, $get('HTTP_REFERER'));
        $this->set(static::USER_AGENT, $get('USER_AGENT'));
    }

    public function get($name, $default = null)
    {
        return $this->has($name) ? $this->attributes[$name] : $default;
    }

    public function set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function has($name)
    {
        return isset($this->attributes[$name]);
    }

    public function forget($name)
    {
        unset($this->attributes[$name]);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    public function __unset($name)
    {
        $this->forget($name);
    }

    public function offsetGet($name)
    {
        return $this->get($name);
    }

    public function offsetSet($name, $value)
    {
        $this->set($name, $value);
    }

    public function offsetExists($name)
    {
        return $this->has($name);
    }

    public function offsetUnset($name)
    {
        $this->forget($name);
    }

    public function toArray()
    {
        return $this->attributes;
    }
}
