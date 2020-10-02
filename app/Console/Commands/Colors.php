<?php

namespace App\Console\Commands;

use App\Libraries\MercadoColors;
use Illuminate\Console\Command;

class Colors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'colors:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get products colors from Mercado Livre public API';

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
     * @return int
     */
    public function handle(MercadoColors $mercadoColors)
    {
        if(! $mercadoColors->getColorsFromMercadoLivre()) {
            $this->error("A error was ocurred while inserting colors to database");
        } else {
            $this->info('Color inserted with success !!!');
        }
    }
}
