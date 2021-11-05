<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class Counter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'counter:test';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
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
        $counterKey = Carbon::now()->startOfMonth()->toDateString() . '::' .
            Carbon::now()->endOfMonth()->toDateString();
        $s3Bucket = Config::get('filesystems.disks.s3.bucket', false);
        $counterFile = 'counters/application-counter.json';
        $counterData = new \stdClass();
        $counterData->$counterKey = (object)[
            'paid' => 0,
            'exempt' => 0,
            'failed' => 0
        ];
        
        if (!Storage::disk('local')->exists($counterFile)) {
            if ($s3Bucket && Storage::disk('s3')->exists($counterFile)) {
                $counterData = (object)json_decode(Storage::disk('s3')->get($counterFile));
                if (!$counterData->$counterKey) {
                    $counterData->$counterKey = (object)[
                        'paid' => 0,
                        'exempt' => 0,
                        'failed' => 0
                    ];
                }
            }
        } else {
            $counterData = (object)json_decode(Storage::disk('local')->get($counterFile));
        }
        
        Storage::disk('local')->put($counterFile, json_encode($counterData, JSON_PRETTY_PRINT));
        if ($s3Bucket) {
            Storage::disk('s3')->put($counterFile, json_encode($counterData, JSON_PRETTY_PRINT));
        }
        
        dd($counterData);
        
        return Command::SUCCESS;
    }
}
