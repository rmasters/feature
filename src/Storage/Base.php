<?php

namespace Feature\Storage;

use Feature\FeatureManager;

abstract class Base
{
    /** @var \Feature\FeatureManager */
    protected $manager;

    /**
     * @param \Feature\FeatureManager $manager Manager to load into
     */
    public function __construct(FeatureManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Load all of the storage's feature/toggles into the manager
     */
    public function load()
    {
        /** @var \Feature\Toggle[]|callable[] $toggles */
        foreach ($this->read() as $feature => $toggles)
        {
            foreach ($toggles as $toggle) {
                $this->manager->enable($feature, $toggle);
            }
        }
    }

    /**
     * Read features and toggles from the storage source
     * @return array Map of feature names to an array of Toggles/callables
     */
    abstract protected function read();
}
