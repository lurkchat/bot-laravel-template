<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Lurkchat\Laravel\Facades\LurkchatBot;

class SetWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:set';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set bot webhook url';

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
        $response = LurkchatBot::checkWebhook();
        if ($response->success && $response->result){
            $this->info('Webhook already set');
            return false;
        }

        $webhook = route('webhook.handle');
        $response = LurkchatBot::setWebhook($webhook);
        if ($response->success){
            $this->info($response->result);
        }else{
            if (is_array($response->result)) {
                foreach ($response->result as $error) {
                    $this->error($error);
                }
            }else{
                $this->error($response->result);
            }
        }

    }
}
