<?php
/**
 * Author: Jun Chen
 */

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetAllUserPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-all-user-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all user points to 0';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::query()->update(['points' => 0]);

        $this->info('All user points have been reset to 0');
    }
}
