<?php

namespace Unctom\EmailShield\Commands;

use Illuminate\Console\Command;

class EmailShieldCommand extends Command
{
    public $signature = 'laravel-email-shield';

    public $description = 'something nice';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}