<?php
/**
 * Author: Jun Chen
 */

namespace Tests\Unit;

use App\Models\Winner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WinnerModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a winner has a user relationship.
     */
    public function test_it_has_user_relationship()
    {
        // Create a user and a winner instance
        $user = User::factory()->create();
        $winner = Winner::factory()->create(['user_id' => $user->id]);

        // Assert that the winner is associated with a user
        $this->assertInstanceOf(User::class, $winner->user);
        $this->assertEquals($user->id, $winner->user->id);
    }

    /**
     * Test that a winner can be created.
     */
    public function test_it_is_mass_assignable()
    {
        // Define attributes for a new Winner instance
        $data = [
            'user_id' => 1,
            'points' => 100,
            'won_at' => now(),
        ];

        // Create a Winner instance
        $winner = Winner::factory()->create($data);

        // Assert that the instance was created with the expected attributes
        $this->assertEquals($data['user_id'], $winner->user_id);
        $this->assertEquals($data['points'], $winner->points);
        $this->assertEquals($data['won_at'], $winner->won_at);
    }
}
