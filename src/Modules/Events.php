<?php

namespace Arkade\Emarsys\Modules;

use Arkade\Emarsys\Entities\Contact;
use Arkade\Emarsys\Entities\Event;
use Illuminate\Support\Collection;

class Events extends AbstractModule
{
    /**
     * Trigger an event for a specific Contact.
     *
     * @param  int  $event
     * @param int $field
     * @param  string  $externalId
     * @param array $data
     * @return Collection|Contact[]
     */
    public function trigger(int $event, int $field, string $externalId, $data)
    {
        return $this->buildCollection(
            $this->client->post('event/' . $event . '/trigger', [
                'json' => [
                    'key_id'      => $field,
                    'external_id' => $externalId,
                    'data'        => [
                        'global' => $data
                    ]
                ]
            ]),
            Event::class
        );
    }

    /**
     * Get event identifiers
     *
     * Emarsys uses an numeric ID reference to a particular event trigger
     * this returns the event identifiers
     *
     * @return Collection
     */
    public function getEvents(){

        $response = $this->client->get('event');

        $events = json_decode((string) $response->getBody(), true)['data'];

        $result = collect([]);

        foreach($events as $event){
            $result->push($event);
        }

        return $result;
    }

}
