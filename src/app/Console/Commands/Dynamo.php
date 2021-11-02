<?php

namespace App\Console\Commands;

use App\Models\Counter;
use Illuminate\Console\Command;

class Dynamo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamo:test';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test out Dynamo connections';
    
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
    public function handle()
    {
        $counter = new Counter();
        $count = $counter->where('key', 'phase2-paid')->first();
     
        if($count) {
            $count->update([
                'count' => $count->count + 1
            ]);
        } else {
            $counter->key = 'phase2-paid';
            $counter->count = 1;
            $counter->save();
        }
        
        return Command::SUCCESS;
    }
    
}
