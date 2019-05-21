<?php

namespace Arkade\Emarsys\Entities;

use Arkade\Emarsys\Concerns;
use Illuminate\Support\Fluent;

abstract class Entity extends Fluent
{
    use Concerns\HasAttributes;
}
