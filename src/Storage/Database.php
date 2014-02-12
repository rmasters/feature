<?php

namespace Feature\Storage;

use Exception;
use ReflectionClass;
use Feature\FeatureManager;
use Feature\Storage;

/**
 * Retrieves feature/toggle definitions from a database
 *
 * Requires each toggle to be a defined class, rather than closures.
 */
class Database extends Base implements Storage
{
    /** @var \Feature\Storage\Database\TableProvider */
    protected $tables;

    /**
     * @param \Feature\FeatureManager $manager
     * @param \Feature\Storage\Database\TableProvider $tables
     */
    public function __construct(FeatureManager $manager, TableProvider $tables)
    {
        parent::__construct($manager);

        $this->tables = $tables;
    }

    protected function read()
    {
        return $this->tables->getFeatureToggles();
    }
}
