<?php

namespace App\Console\Commands;

use App\Models\Want;
use Illuminate\Console\Command;

class ExpireWants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wants:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close wants that have passed their expiration date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredCount = Want::where('status', 'open')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);

        $this->info("Expired {$expiredCount} want(s).");

        return 0;
    }
}
