<?php

namespace Feature\Storage\Database;

/**
 * Holds table names and provides merging functions
 */
abstract class TableProvider
{
    /** @var string Table name of the table containing feature/toggle pairs */
    public $togglesTable = 'toggles';

    /** @var string Table name of the table containing toggle constructor args */
    public $toggleArgumentsTable = 'toggle_arguments';

    public function getFeatureToggles()
    {
        $toggles = $this->getToggles();
        $arguments = $this->getToggleArguments();
        $arguments = $this->groupArguments($arguments);

        return $this->mergeTogglesAndArguments($toggles, $arguments);
    }

    /**
     * Creates a new hash of feature names, to their toggles, to their arguments
     *
     * @return array
     */
    protected function mergeTogglesAndArguments($toggles, $arguments)
    {
        $features = [];
        foreach ($toggles as $toggle) {
            $featureName = $toggle['feature'];
            $toggleName = $toggle['toggle'];
            $toggleId = $toggle['id'];
            
            // Add feature if it doesn't exist
            if (!isset($features[$featureName])) {
                $features[$featureName] = [];
            }

            $feature = ['name' => $toggleName];
            if (isset($arguments[$toggleId])) {
                $feature['params'] = $this->mergeArguments($arguments[$toggleId]);
            }

            $features[$featureName][] = $feature;
        }

        return $features;
    }

    /**
     * Groups arguments by toggle_id
     * @return array
     */
    protected function groupArguments($arguments)
    {
        $args = [];
        foreach ($arguments as $arg) {
            $toggleId = $arg['toggle_id'];

            // Create group
            if (!isset($args[$toggleId])) $args[$toggleId] = [];

            $args[$toggleId][] = $arg;
        }

        return $args;
    }

    /**
     * Merges an argument list, such that multiple items with the same argument
     * position become an indexed array.
     *
     * @return array
     */
    protected function mergeArguments($arguments)
    {
        $args = [];
        foreach ($arguments as $arg) {
            $position = $arg['position'];
            $value = $arg['value'];

            if (isset($args[$position])) {
                // If a value already exists at this position, create an array
                if (!is_array($args[$position])) {
                    $args[$position] = [$args[$position]];
                }
                $args[$position][] = $value;
            } else {
                $args[$position] = $value;
            }
        }

        return $args;
    }

    /**
     * @return array Array of hashes containing id, feature and toggle
     *
     * [
     *   [
     *     'id' => 1,
     *     'feature' => 'new_blog_theme',
     *     'toggle' => 'Feature\Toggles\Dummy'
     *   ]
     * ]
     */
    abstract protected function getToggles();

    /**
     * @return array Array of hashes containing toggle id, arg position and value
     *
     * [
     *   [
     *     'toggle_id' => 1, (refs getToggles().id)
     *     'position' => 0, (zero-indexed argument position)
     *     'value' => '127.0.0.1',
     *   ]
     * ]
     */
    abstract protected function getToggleArguments();
}
