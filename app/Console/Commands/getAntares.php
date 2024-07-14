<?php

namespace App\Console\Commands;

use App\Http\Controllers\HistoriesController;
use Illuminate\Console\Command;

class getAntares extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:antares';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $con = new HistoriesController();
        $antares = $con->antares();
        $this->info($antares);
    }
}
