<?php
/**
 * Author: Jun Chen
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Jobs\GenerateQRCodeJob;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::orderByDesc('points')->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        // Dispatch the job to generate and store the QR code
        GenerateQRCodeJob::dispatch($user);

        return $user;
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($request->action === 'dec' && $user->points > 0) {
            $user->points -= 1;
        } else if ($request->action === 'inc') {
            $user->points += 1;
        }

        try {
            $user->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Unable to update user points'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Unable to delete user'], 500);
        }
    }

    /**
     * Group users by points and calculate the average age
     */
    public function stats()
    {
        // SELECT points, AVG(age) as average_age, GROUP_CONCAT(name) as names FROM users GROUP BY points;
        $groupedUserInfo = User::select(
            'points',
            DB::raw('AVG(age) as average_age'),
            DB::raw('GROUP_CONCAT(name) as names')
        )
            ->groupBy('points')
            ->get();

        // Format the grouped user info
        $formattedUserInfo = [];

        foreach ($groupedUserInfo as $group) {
            // Round the average age
            $averageAge = round($group->average_age, 0);

            // Convert the comma-separated list of names into an array
            $userNames = explode(',', $group->names);

            // Add the formatted user info to the array
            $formattedUserInfo[$group->points] = [
                'names' => $userNames,
                'average_age' => $averageAge,
            ];
        }

        krsort($formattedUserInfo);
        return response()->json($formattedUserInfo);
    }
}
