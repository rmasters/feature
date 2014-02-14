<?php

namespace Feature;

/**
 * A storage provider
 */
interface Storage
{
   /**
    * Load all of the storage's feature/toggles into the manager
    */
   public function load();
}