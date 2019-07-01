<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Channel;

class CreateChannel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channel:create
                            {name : The name of the Slack channel}
                            {web_hook : The URL of the Slack channel web hook}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Slack channel for reporting';

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

        $channel = Channel::where('name', $this->argument('name'))
                          ->where('web_hook', $this->argument('web_hook'))->first();

        if ($channel) {
            $this->error("Channel {$this->argument('name')} already exists.");
            return;
        }

        $new_channel = new Channel; 
        $new_channel->name = $this->argument('name');
        $new_channel->web_hook = $this->argument('web_hook');
        $new_channel->save();

        $this->info("Channel {$this->argument('name')} saved successfully.");
        return;
    }
}
