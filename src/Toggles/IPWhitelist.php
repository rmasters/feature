<?php

namespace Feature\Toggles;

use Feature\Toggle;
use Feature\User;

class IPWhitelist implements Toggle
{
    /** @var array */
    protected $ips;

    public function __construct(array $ips)
    {
        $this->ips = $ips;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(User $user)
    {
        return in_array($user->remote_addr, $this->ips);
    }
}
