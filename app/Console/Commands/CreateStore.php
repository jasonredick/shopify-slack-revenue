<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Store;
use App\Channel;

class CreateStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:create
                            {name : The name of the store}
                            {handle : The handle of the store, [handle].myshopify.com}
                            {api_key : The API Key provided by Shopify}
                            {api_password : The API Password provied by Shopify}
                            {channel : Name of the Slack channel to report to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add store for revenue reporting';

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
        // Input validation.

        try {
            $channel = Channel::where('name', $this->argument('channel'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $this->error("Channel {$this->argument('channel')} does not exist. Please create the channel first.");
            return;
        }

        $store = Store::where('handle', $this->argument('handle'))
                      ->where('api_key', $this->argument('api_key'))->first();

        if ($store) {
            $this->error("Store {$this->argument('name')} already exists.");
            return;
        }

        $new_store = $channel->stores()->create([
            'name' => $this->argument('name'),
            'handle' => $this->argument('handle'),
            'api_key' => $this->argument('api_key'),
            'api_password' => $this->argument('api_password')
        ]);

        if (!$new_store->id) {
            $this->error("Store {$this->argument('name')} failed to save, please try again.");
            return;
        }

        $this->info("Store {$this->argument('name')} saved successfully.");
        return;
    }
}
