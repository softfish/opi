<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OrderProcessor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:processor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will get all open order and send it off to the event order processing. ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
