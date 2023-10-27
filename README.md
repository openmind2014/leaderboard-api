# Leaderboard API

This is a PHP-based RESTful API for managing user data, including creating, updating, deleting, and retrieving user information. It also provides additional functionality for statistical data and background job processing.

## Author
- **Name**: Jun Chen
- **Email**: juncwork@gmail.com

## Table of Contents
- [Installation](#installation)
- [API Routes](#api-routes)
- [Console Commands](#console-commands)
- [Jobs](#jobs)
- [Scheduled Tasks](#scheduled-tasks)
- [Test Cases](#test-cases)

## Installation

To use this code, follow these steps:

1. Clone the repository to your local environment.

2. Set up a Laravel environment if you haven't already. `composer install`.

3. Configure the database settings in the `.env` file. `CREATE DATABASE leaderboard;`

4. Run database migrations to create tables using the `php artisan migrate` command.
   - Refresh Database and Seed: `php artisan migrate:fresh --seed`

5. Start a Laravel development server using the `php artisan serve` command.

6. You can now access the API using the specified routes.


## API Routes

The API provides the following routes:

### Users
- `GET /api/v1/users` - Retrieve a list of users sorted by points.
- `POST /api/v1/users` - Create a new user.
  ```
  Content-Type: application/json
  {
    "name": "Nick",
    "age": 18,
    "address": "Vancouver"
  }
  ```
- `GET /api/v1/users/{user}` - Retrieve a specific user by ID.
- `PUT /api/v1/users/{user}` - Update a user's points (increment or decrement).
  - The update operation depends on the `action` parameter in the request.
  - If 'action' is 'dec' and the user has more than 0 points, it decreases the points by 1.
  - If 'action' is 'inc', it increases the user's points by 1.
  - The route returns a list of users ordered by points in descending order.
  ```
  Content-Type: application/json
  {
    "action": "inc"
  }
  ```
- `DELETE /api/v1/users/{user}` - Delete a user.
- `GET /api/v1/stats/users` - Retrieve statistics about users, grouped by points, and their average age.


## Console Commands

1. **IdentifyWinner** (IdentifyWinner.php):
    - A command to identify the winner of a contest.
    - Identifies the user with the highest points as the winner.
    - Creates a `Winner` record and logs the result.
#### Usage:
```bash
php artisan app:identify-winner
```

2. **ResetAllUserPoints** (ResetAllUserPoints.php):
    - A command to reset all user points to 0.
    - Updates all user points to 0 in the database.
#### Usage:
```bash
php artisan app:reset-all-user-points
```

## Scheduled Tasks

The application includes a scheduled task to run the `IdentifyWinner` command every five minutes using Laravel's scheduling mechanism.
- Add a single cron configuration to your server:
- `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`

## Jobs

1. **GenerateQRCodeJob** (GenerateQRCodeJob.php):
    - A job for generating QR codes for user addresses.
    - Utilizes an external API to generate QR codes.
    - Stores the QR code image locally in the `public` disk.
#### Usage:
```bash
php artisan queue:work
```

## Test Cases

I have created a set of test cases to verify the functionality of the application. These tests cover various aspects of the API, controllers, and other components. The tests are designed to ensure that the application works as expected and to catch any issues or regressions.

### Controller Tests

These tests validate the functionality of the User Controller, including user creation, retrieval, updating points, and deletion.

- `test_it_can_return_list_of_users_ordered_by_points`: Validates the ordering of users by points.
- `test_it_can_create_a_user`: Tests user creation.
- `test_it_can_show_a_user`: Tests user retrieval.
- `test_it_can_update_user_points`: Validates user points update (increment and decrement).
- `test_it_can_delete_a_user`: Tests user deletion.
- `test_it_can_group_users_by_points`: Validates grouping of users by points and calculating average age.

### Model Tests

I have included tests for the User and Winner models to ensure their attributes and relationships are correct.

- `UserModelTest`:
    - `test_create_user`: Validates user creation.
- `WinnerModelTest`:
    - `test_it_has_user_relationship`: Ensures the Winner model has a user relationship.
    - `test_it_is_mass_assignable`: Validates mass assignment of Winner attributes.

### Command Tests

I have tests for the console commands as well, ensuring they function correctly.

- `IdentifyWinnerTest`:
    - `testWinnerDeclared`: Validates the identification of a winner.
    - `testNoWinnerDeclaredDueToTie`: Tests when no winner is declared due to a tie.
- `ResetAllUserPointsTest`: Tests the reset of all user points to 0.

#### Usage:
```bash
php artisan test
```
