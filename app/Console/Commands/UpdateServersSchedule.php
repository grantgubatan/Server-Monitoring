<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\HomeController;
use Server;

class UpdateServersSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateServersSchedule:updateServer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Servers';

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
        $hc = new HomeController();
        $hc->updateServerScheduler();

    }
}
