<?php
/**
 * Author: Jun Chen
 */

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResetAllUserPointsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that all user points can be reset to 0.
     */
    public function testResetAllUserPointsCommand()
    {
        // Create some test users with non-zero points
        User::factory()->create(['name' => 'User1', 'points' => 10]);
        User::factory()->create(['name' => 'User2', 'points' => 15]);
        User::factory()->create(['name' => 'User3', 'points' => 20]);

        // Expect this output
        $this->artisan('app:reset-all-user-points')
            ->expectsOutput('All user points have been reset to 0');

        // Check that all user points are reset to 0 in the database
        $this->assertDatabaseHas('users', ['points' => 0]);
    }
}
