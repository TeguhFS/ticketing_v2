<?php

namespace App\Console\Commands;

use App\Jobs\ExpireOrdersJob;
use Illuminate\Console\Command;

class ExpireOrders extends Command
{
    protected $signature   = 'orders:expire';
    protected $description = 'Expire pending orders that have passed their expiration time';

    public function handle(): void
    {
        $this->info('Processing expired orders...');

        ExpireOrdersJob::dispatchSync();

        $this->info('Done! Expired orders processed successfully.');
    }
}
