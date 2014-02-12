<?php

namespace Feature;

interface Toggle
{
    /**
     * Whether a feature is enabled for the given user
     * @param \Feature\User $user User attributes
     * @return boolean
     */
    public function __invoke(User $user);
}
