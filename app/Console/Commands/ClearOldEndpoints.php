<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Resource;

class ClearOldEndpoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'endpoints:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear API resources older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = Resource::where('created_at', '<', now()->subHours(24))->delete();
        $this->info("Deleted {$deleted} old resources.");
    }
}
