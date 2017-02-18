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
    protected $description = 'This command will get all open orders and send it off to the event OrderStatusEvaluation for processing. ';

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
        $orders = \App\Models\Order::where('status', 'In porgress')->all();
        
        foreach ($orders as $order){
            event(new \App\Events\OrderStatusEvaluation($order));
        }
        
    }
}
