<?php
/**
 * Author: Jun Chen
 */

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Winner;
use Illuminate\Console\Command;

class IdentifyWinner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:identify-winner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Identify the winner of the contest';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $highestPoints = User::max('points');
        $potentialWinners = User::where('points', $highestPoints)->get();

        if ($potentialWinners->count() === 1) {
            $winner = $potentialWinners->first();
            Winner::create([
                'user_id' => $winner->id,
                'points' => $winner->points,
                'won_at' => now(),
            ]);
            $this->info('A winner has been declared.');
        } else {
            $this->info('No winner declared due to a tie.');
        }
    }
}
