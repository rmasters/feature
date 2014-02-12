<?php

namespace Feature;

use InvalidArgumentException;

class FeatureManager
{
    /** @var array Hash of feature names to a list of callables */
    protected $toggles;
    /** @var \Feature\User User attributes */
    protected $user;

    public function __construct()
    {
        $this->toggles = [];
        $this->user = null;
    }

    /**
     * Whether the feature is enabled
     * @param string $feature Feature name
     * @param mixed|null $user User to check, or the current user
     * @return boolean
     */
    public function can($feature, $user = null)
    {
        // If no toggles are registered, deny access
        if (!isset($this->toggles[$feature])) {
            return false;
        }

        // If a user is passed use that for toggles
        $user = $user ?: $this->user;

        // For each feature toggle, check if the user can access it
        foreach ($this->toggles[$feature] as $toggle) {
            if ($toggle($user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add a toggle for a feature
     * @param string $feature Feature name
     * @param callable $toggle A callable that accepts a single argument and returns a boolean
     * @see \Feature\Toggle
     */
    public function enable($feature, callable $toggle)
    {
        if (!isset($this->toggles[$feature])) {
            $this->toggles[$feature] = [];
        }

        $this->toggles[$feature][] = $toggle;
    }

    /**
     * Set user attributes
     * @param \Feature\User $attributes
     */
    public function setUser(User $attributes)
    {
        $this->user = $attributes;
    }
}
