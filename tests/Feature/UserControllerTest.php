<?php
/**
 * Author: Jun Chen
 */

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test that a ordered list of users can be returned.
     */
    public function test_it_can_return_list_of_users_ordered_by_points()
    {
        // Create some user records with random points
        $users = User::factory()->count(5)->create();

        // Ensure the users are ordered by points in descending order
        $response = $this->getJson('/api/v1/users');
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals(
            $users->sortByDesc('points')->pluck('id')->toArray(),
            array_column($data, 'id')
        );
    }

    /**
     * Test that a user can be created.
     */
    public function test_it_can_create_a_user()
    {
        // Create a user with the given data
        $userData = [
            'name' => 'John Doe',
            'age' => 30,
            'address' => '123 Main St',
        ];

        // Ensure the user is created with the given data
        $response = $this->postJson('/api/v1/users', $userData);
        $response->assertStatus(201);
        $response->assertJsonFragment($userData);
    }

    /**
     * Test that a user can be updated.
     */
    public function test_it_can_show_a_user()
    {
        $user = User::factory()->create();

        // Ensure the user is returned with the expected data
        $response = $this->getJson("/api/v1/users/{$user->id}");
        $response->assertStatus(200);
        $response->assertJson(['id' => $user->id]);
    }

    /**
     * Test that a user points can be updated.
     */
    public function test_it_can_update_user_points()
    {
        $user = User::factory()->create(['points' => 10]);

        // Test the 'update' action to increment points
        $response = $this->putJson("/api/v1/users/{$user->id}", ['action' => 'inc']);
        $response->assertStatus(200);
        $this->assertEquals(11, $user->fresh()->points);

        // Test the 'update' action to decrement points
        $response = $this->putJson("/api/v1/users/{$user->id}", ['action' => 'dec']);
        $response->assertStatus(200);
        $this->assertEquals(10, $user->fresh()->points);
    }

    /**
     * Test that a user can be deleted.
     */
    public function test_it_can_delete_a_user()
    {
        $user = User::factory()->create();

        // Ensure the user is deleted
        $response = $this->deleteJson("/api/v1/users/{$user->id}");
        $response->assertNoContent();

        // Ensure the user has been deleted from the database
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /**
     * Test that a user can be grouped by points.
     */
    public function test_it_can_group_users_by_points()
    {
        // Create users with different points
        User::factory()->create(['points' => 10, 'name' => 'User 1', 'age' => 25]);
        User::factory()->create(['points' => 20, 'name' => 'User 2', 'age' => 30]);
        User::factory()->create(['points' => 20, 'name' => 'User 3', 'age' => 35]);
        User::factory()->create(['points' => 30, 'name' => 'User 4', 'age' => 40]);

        // Get a response for grouping users by points
        $response = $this->getJson('/api/v1/stats/users');

        // Assert that the response is successful
        $response->assertStatus(200);

        // Define the expected grouped user data
        $expectedGroupedData = [
            30 => ['names' => ['User 4'], 'average_age' => 40],
            20 => ['names' => ['User 2', 'User 3'], 'average_age' => 33],
            10 => ['names' => ['User 1'], 'average_age' => 25],
        ];

        // Assert that the response data matches the expected grouped data
        $this->assertEquals($expectedGroupedData, $response->json());
    }
}
