<?php

namespace Arkade\Emarsys\Entities;

use Illuminate\Support\Fluent;
use Illuminate\Support\Collection;
use Arkade\Emarsys\Exceptions;

class Contact extends Entity
{
    /**
     * Create a new contact instance.
     *
     * @param  array|object  $attributes
     * @return void
     * @throws Exceptions\EmarsysException
     */
    public function __construct($attributes = [])
    {
        if(!empty($attributes['ids'])){
            $attributes['id'] = $attributes['ids'][0];
            unset($attributes['ids']);
        }
        if(!empty($attributes['errors'])){
            throw new Exceptions\EmarsysException(current(current($attributes['errors'])));
        }
        parent::__construct($attributes);
    }
}
