<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class ApplicationCounter
{
    /**
     * @var array
     */
    private $services = [
        ServiceBranch::RAF,
        ServiceBranch::HOME_GUARD,
        ServiceBranch::ARMY,
        ServiceBranch::NAVY
    ];

    /**
     * @var array
     */
    private $statuses = [
        Application::APPLICATION_PAID,
        Application::APPLICATION_EXEMPT,
        Application::APPLICATION_FAILED
    ];

    /**
     * @var object
     */
    private ?object $counterTemplate = null;

    /**
     * @var object
     */
    private ?object $data = null;

    /**
     * @var object
     */
    private ?object $currentNode = null;

    /**
     * @var string
     */
    private ?string $key = null;

    /**
     * @var ApplicationCounter|null
     */
    private static ?ApplicationCounter $instance = null;

    /**
     *
     */
    private function __construct()
    {
        $this->key = Carbon::now()->startOfMonth()->toDateString() . '::' .
            Carbon::now()->endOfMonth()->toDateString();

        $this->counterTemplate = (object)[
            Application::APPLICATION_PAID => 0,
            Application::APPLICATION_EXEMPT => 0,
            Application::APPLICATION_FAILED => 0,
            'last_updated' => '0000-00-00 00:00:00',
            'total' => 0
        ];

        $this->load();
    }

    /**
     * @return ApplicationCounter
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new ApplicationCounter();
        }

        return self::$instance;
    }

    /**
     * @return void
     */
    public function setLastUpdated()
    {
        $this->data->LAST_UPDATE = date('Y-m-d H:i:s');
    }

    /**
     * @return void
     */
    public function paid($service)
    {
        $this->increment($service, Application::APPLICATION_PAID);
    }

    /**
     * @return void
     */
    public function exempt($service)
    {
        $this->increment($service, Application::APPLICATION_EXEMPT);
    }

    /**
     * @return void
     */
    public function failed($service)
    {
        $this->increment($service, Application::APPLICATION_FAILED);
    }

    /**
     * @param $service
     * @param $status
     * @return void
     */
    public function increment($service, $status)
    {
        if(!property_exists($this->data->{$this->key}->$service, $status))
            $this->data->{$this->key}->$service->$status = 0;

        if(!property_exists($this->data->{$this->key}->TOTAL, $status))
            $this->data->{$this->key}->TOTAL->$status = 0;

        if(!property_exists($this->data->TOTAL->$status, $status))
            $this->data->TOTAL->$status->$status = 0;

        $this->data->{$this->key}->$service->$status++;
        $this->data->{$this->key}->TOTAL->$status++;
        $this->data->{$this->key}->$service->TOTAL++;
        $this->data->TOTAL->$status++;
        $this->save();
    }

    /**
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function load()
    {
        if (!$this->data) {
            $s3Bucket = Config::get('filesystems.disks.s3.bucket', false);

            if (!Storage::disk('local')->exists(Constant::COUNTER_FILE)) {
                if ($s3Bucket && Storage::disk('s3')->exists(Constant::COUNTER_FILE)) {
                    $this->data = (object)json_decode(Storage::disk('s3')->get(Constant::COUNTER_FILE, true));
                }
            } else {
                $this->data = (object)json_decode(Storage::disk('local')->get(Constant::COUNTER_FILE, true));
            }
        }

        if (!$this->data) {
            $this->data = (object)[];
        }

        if (!property_exists($this->data, 'GRAND_TOTAL')) {
            $this->data->GRAND_TOTAL = (object)[
                Application::APPLICATION_PAID => 0,
                Application::APPLICATION_EXEMPT => 0,
                Application::APPLICATION_FAILED => 0,
                'TOTAL' => 0
            ];
        }

        if (!property_exists($this->data, $this->key)) {
            $this->data->{$this->key} = (object)[
                'GRAND_TOTAL' => (object)[
                    Application::APPLICATION_PAID => 0,
                    Application::APPLICATION_EXEMPT => 0,
                    Application::APPLICATION_FAILED => 0,
                    'TOTAL' => 0
                ]
            ];

            foreach ($this->services as $service) {
                $this->data->{$this->key}->$service = (object)[
                    Application::APPLICATION_PAID => 0,
                    Application::APPLICATION_EXEMPT => 0,
                    Application::APPLICATION_FAILED => 0,
                    'TOTAL' => 0
                ];
            }
        }

        $this->validate();
    }

    /**
     * @return void
     */
    public function save()
    {
        $this->setLastUpdated();
        $s3Bucket = Config::get('filesystems.disks.s3.bucket', false);

        Storage::disk('local')->put(Constant::COUNTER_FILE, json_encode($this->data, JSON_PRETTY_PRINT));
        if ($s3Bucket) {
            Storage::disk('s3')->put(Constant::COUNTER_FILE, json_encode($this->data, JSON_PRETTY_PRINT));
        }
    }

    /**
     * @return void
     */
    private function validate()
    {
        $grandTotal = (object)[
            Application::APPLICATION_PAID => 0,
            Application::APPLICATION_EXEMPT => 0,
            Application::APPLICATION_FAILED => 0,
            'TOTAL' => 0
        ];

        if (!$this->data->GRAND_TOTAL) {
            $this->data->GRAND_TOTAL = (object)[
                Application::APPLICATION_PAID => 0,
                Application::APPLICATION_EXEMPT => 0,
                Application::APPLICATION_FAILED => 0,
                'TOTAL' => 0
            ];
        }

        foreach ($this->data as $key => $value) {
            if ($key != 'GRAND_TOTAL' && $key != 'LAST_UPDATE') {
                if (!property_exists($this->data->$key, 'TOTAL')) {
                    $this->data->$key->TOTAL = 0;
                }

                $keyTotal = 0;
                foreach ($this->services as $service) {
                    $serviceTotal = 0;
                    if (property_exists($this->data->$key, $service)) {
                        foreach ($this->statuses as $status) {
//                            if (gettype($this->data->$key->$service) == 'object') {
                                if (property_exists($this->data->$key->$service, $status)) {
                                    $keyTotal += $this->data->$key->$service->$status;
                                    $serviceTotal += $this->data->$key->$service->$status;
                                    $grandTotal->$status += $this->data->$key->$service->$status;
                                } else {
                                    $this->data->$key->$service->$status = 0;
                                }
//                            }
                        }

//                        if (gettype($this->data->$key->$service) == 'object') {
                            if (!property_exists($this->data->$key->$service, 'TOTAL')) {
                                $this->data->$key->$service->TOTAL = 0;
                            }

                            $this->data->$key->$service->TOTAL = $serviceTotal;
//                        }

                    }
                }

                // Count up legacy data
                foreach ($this->statuses as $status) {
                    if (property_exists($this->data->$key, $status)) {
                        $grandTotal->$status += $this->data->$key->$status;
                    }
                }
            }

            if (gettype($this->data->$key) == 'object') {
                $this->data->$key->TOTAL = $keyTotal;
            }
        }

        foreach ($this->statuses as $status) {
            $grandTotal->TOTAL += $grandTotal->$status;
        }

        $this->data->GRAND_TOTAL = $grandTotal;
    }
}