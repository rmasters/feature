<?php

namespace Feature\Storage;

use Exception;
use ReflectionClass;
use Feature\FeatureManager;
use Feature\Storage;

/**
 * Array-based feature definitions
 *
 * Requires each toggle to be a defined class, rather than closures.
 */
class Structured extends Base implements Storage
{
    /** @var array */
    protected $config;

    public function __construct(FeatureManager $manager, array $config)
    {
        parent::__construct($manager);
        $this->config = $config;
    }

    protected function read()
    {
        /**
         * Expected array structure:
         *
         * [
         *   'feature' => [
         *     // A named Toggle and it's constructor arguments
         *     ['name' => 'Feature\Toggles\WhitelistIP',
         *      'params' => [
         *        ['127.0.0.1', '192.168.0.1']
         *      ]
         *     ]
         *
         *     // more toggles
         *   ],
         *
         *   // more features
         * ]
         */

        $data = array();

        foreach ($this->config as $feature => $toggles) {
            $data[$feature] = [];

            foreach ($toggles as $toggle) {
                if (is_callable($toggle)) {
                    // Closures or __invokables can be added straight away
                    $data[$feature][] = $toggle;
                } else if (is_array($toggle)) {
                    // Arrays indicate a toggle that needs to be constructed
                    if (!isset($toggle['name'])) {
                        throw new Exception('Toggle configuration must have a toggle name');
                    }

                    $params = isset($toggle['params']) ? $toggle['params'] : [];

                    // Instantiate the toggle
                    $reflection = new ReflectionClass($toggle['name']);
                    $data[$feature][] = $reflection->newInstanceArgs([$params]);
                } else {
                    throw new Exception('Toggles must be an array or callable');
                }
            }
        }

        return $data;
    }
}
