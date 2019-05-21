<?php

namespace Arkade\Emarsys\Modules;

use Arkade\Emarsys\Client;
use Arkade\Emarsys\Concerns;

abstract class AbstractModule
{
    use Concerns\MutatesResponses;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Abstract Module constructor.
     *
     * @param Client|null $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
