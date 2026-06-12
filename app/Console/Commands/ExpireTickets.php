<?php

namespace App\Console\Commands;

use App\Jobs\ExpireTicketsJob;
use Illuminate\Console\Command;

class ExpireTickets extends Command
{
    protected $signature   = 'tickets:expire';
    protected $description = 'Expire active tickets where event has already started but user did not attend';

    public function handle(): void
    {
        $this->info('Processing expired tickets...');

        ExpireTicketsJob::dispatchSync();

        $this->info('Done! Expired tickets processed successfully.');
    }
}
