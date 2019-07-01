<?php

namespace App\Console\Commands;

use App\Channel;
use App\Traits\ReportTraits;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetAfternoonReports extends Command
{
    use ReportTraits;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:afternoon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs report for today\'s sales';

    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $parameters = [];
        $parameters['limit'] = 250;
        $parameters['processed_at_min'] = Carbon::today('America/New_York')->toAtomString();;
        $parameters['financial_status'] = 'any';
        $parameters['status'] = 'any';
        $parameters['fields'] = 'name,total_price';

        $channels = Channel::all();
        foreach ($channels as $channel) {
            $this->processChannel($channel, $parameters, 'Today\'s');
        }
    }
}
