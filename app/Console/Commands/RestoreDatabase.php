<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RestoreDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore the database';

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
        $file = database_path('seeds/data.sql');

        if (file_exists($file)) {
            try {
                app('db')->unprepared(file_get_contents($file));

                $this->info('The database has been restored successfully.');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        } else {
            $this->error('The data file "' . $file . '" do not exists.');
        }
    }
}
