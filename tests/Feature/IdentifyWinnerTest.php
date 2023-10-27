<?php
/**
 * Author: Jun Chen
 */

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdentifyWinnerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a winner can be identified.
     */
    public function testWinnerDeclared()
    {
        // Create some test users with points
        User::factory()->create(['name' => 'User1', 'points' => 10]);
        User::factory()->create(['name' => 'User2', 'points' => 15]);
        User::factory()->create(['name' => 'User3', 'points' => 20]);

        // Expect this output
        $this->artisan('app:identify-winner')
            ->expectsOutput('A winner has been declared.');

        // Ensure a winner record is created
        $this->assertDatabaseCount('winners', 1);
        $this->assertDatabaseHas('winners', ['points' => 20]);
    }

    public function testNoWinnerDeclaredDueToTie()
    {
        // Create test users with the same highest points
        User::factory()->create(['name' => 'User1', 'points' => 10]);
        User::factory()->create(['name' => 'User2', 'points' => 15]);
        User::factory()->create(['name' => 'User3', 'points' => 15]);

        // Expect this output
        $this->artisan('app:identify-winner')
            ->expectsOutput('No winner declared due to a tie.');

        // Ensure no winner record is created
        $this->assertDatabaseCount('winners', 0);
    }

}
