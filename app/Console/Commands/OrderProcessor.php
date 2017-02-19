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
        $this->info('Loading order still in progress state..');
        $orders = \App\Models\Order::where('status', '=', 'In progress')->get();
        
        $this->info(count($orders) . ' of orders found. ');
        if (! empty($orders) && count($orders) > 0) {
            $this->info('Start order evaluation...');
            foreach ($orders as $order) {
                $this->info('checking order (' . $order->id . ')...');
                event(new \App\Events\OrderStatusEvaluation($order));
                $this->info('checking finished.');
            }
        }
        $this->info('Order evaluation end..');
    }
}
