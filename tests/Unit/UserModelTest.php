<?php
/**
 * Author: Jun Chen
 */

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can be created.
     */
    public function test_create_user()
    {
        // Create a user instance
        $user = User::factory()->create([
            'name' => 'John Doe',
            'age' => 30,
            'points' => 50,
            'address' => '123 Main St',
        ]);

        // Assert that the user was created with the expected attributes
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals(30, $user->age);
        $this->assertEquals(50, $user->points);
        $this->assertEquals('123 Main St', $user->address);
    }
}
