<?php

namespace Feature\Laravel;

use Illuminate\Support\Facades\Facade;

class Feature extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'feature';
    }
}
