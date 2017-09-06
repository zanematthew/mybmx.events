<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\User as User;
use App\Event as Event;
use App\Venue as Venue;
use App\Schedule as Schedule;

class LibraryTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * Sign in User
     * Create an Event
     * Add Item to Library
     * Remove Item from Library
     *
     * @group library
     */
    public function testToggleItemToLibrary()
    {
        Passport::actingAs(factory(User::class)->create());

        $eventId = factory(Event::class)->create()->pluck('id')->first();
        $response = $this->post(route('library.toggle.item', [
            'item_id'   => $eventId,
            'item_type' => 'event',
        ]), [
            'item_id'   => $eventId,
            'item_type' => 'event',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'attached' => [
                    0 => $eventId
                ],
                'detached' => [],
            ]);

        $response = $this->post(route('library.toggle.item', [
            'item_id'   => $eventId,
            'item_type' => 'event',
        ]), [
            'item_id'   => $eventId,
            'item_type' => 'event',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'attached' => [],
                'detached' => [
                    0 => $eventId
                ],
            ]);
    }

    /**
     * Sign in User
     * Create 3 Events
     * Create 2 Additional Venues
     * Create 1 schedule
     * Send GET request
     * @group library
     */
    public function testGetItemsFromLibraryOrganizedByType()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $items             = [];
        $items['event']    = factory(Event::class, 3)->create()->pluck('id')->toArray();
        $items['venue']    = factory(Venue::class, 2)->create()->pluck('id')->toArray();
        $items['schedule'] = factory(Schedule::class)->create([
            'user_id' => $user->id
        ])->pluck('id')->toArray();

        foreach ($items as $type => $ids) {
            foreach ($ids as $id) {
                $response = $this->post(route('library.toggle.item', [
                    'item_id'   => $id,
                    'item_type' => $type,
                ]), [
                    'item_id'   => $id,
                    'item_type' => $type,
                ]);
            }
        }

        // Get ALL items from User Library
        $response = $this->get(route('library.get.items'));
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'event'    => [],
                'venue'    => [],
                'schedule' => [],
            ]);
    }
}
