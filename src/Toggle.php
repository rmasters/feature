<?php

namespace Feature;

interface Toggle
{
    /**
     * Whether a feature is enabled for the given user
     * @param mixed $user User attributes
     * @return boolean
     */
    public function __invoke($user);
}
