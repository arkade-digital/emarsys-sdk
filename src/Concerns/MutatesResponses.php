<?php

namespace Arkade\Emarsys\Concerns;

use Arkade\Emarsys\Entities;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;

trait MutatesResponses
{
    /**
     * Transform the given entity with the given transformer.
     *
     * @param  array  $data
     * @param  string|callable  $transformer
     * @return mixed
     */
    protected function transformEntity(array $data, $transformer)
    {
        if (! is_callable($transformer) && class_exists($transformer)) {
            $transformer = function (array $data) use ($transformer) {
                return new $transformer($data);
            };
        }

        return $transformer($data);
    }

    /**
     * Build an entity from the given response.
     *
     * @param  Response  $response
     * @param  string|callable  $transformer
     * @return mixed
     */
    protected function buildEntity(Response $response, $transformer)
    {
        return $this->transformEntity(
            json_decode((string) $response->getBody(), true)['data'],
            $transformer
        );
    }

    /**
     * Build a collection from the given response.
     *
     * @param  Response  $response
     * @param  string|callable  $transformer
     * @return Collection
     */
    protected function buildCollection(Response $response, $transformer)
    {
        $response = json_decode((string) $response->getBody(), true)['data'];
        if(empty($response)) return new Collection();
        if(!empty($response['errors'])) return new Collection();
        return (new Collection(
            $response['result']
        ))->map(function (array $row) use ($transformer) {
            return $this->transformEntity($row, $transformer);
        });
    }
}