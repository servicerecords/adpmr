<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CF extends Command
{
    /**
     * @var string
     */
    private $iniFile = './cf.ini';

    /**
     * @var string
     */
    private $appName = 'active';

    /**
     * @var null|string
     */
    private $environment = null;

    /**
     * @var null|string
     */
    private $restage = false;

    /**
     * @var array
     */
    private $foundEnvironments = [];

    /**
     * @var array
     */
    private $settings = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cf:set-env';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set all Cloud Foundry environment settings based on a given INI file';

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
        $this->iniFile = $this->ask('Enter the INI file to use', $this->iniFile);
        $this->readIniFile();
        $this->appName = $this->ask('Enter the App name to target', $this->appName);

        if ($this->foundEnvironments) {
            $this->environment = $this->choice('Optional environment to target, enter it here', $this->foundEnvironments);
        }

        if ($this->confirm('Automatically restage after setting environment?')) {
            $this->restage = true;
        }

        $settings = [];

        foreach ($this->settings as $key => $value) {
            if (!is_array($value)) {
                $settings[$key] = $value;
            }
        }

        if ($this->environment) {
            foreach ($this->settings[$this->environment] as $key => $value) {
                $settings[$key] = $value;
            }
        }

        ksort($settings);
        $this->settings = $settings;
        unset($settings);

        if ($this->confirm('All set. Ready to set up Cloud Foundry app', true)) {
            foreach ($this->settings as $key => $value) {
                $this->setEnvironment($key, $value);
            }
        }

        return 0;
    }

    protected function readIniFile()
    {
        if (file_exists($this->iniFile)) {
            $this->settings = parse_ini_file($this->iniFile, true, INI_SCANNER_RAW);

            foreach ($this->settings as $key => $value) {
                if (is_array($value)) {
                    $this->foundEnvironments[] = $key;
                }
            }
        }
    }

    protected function setEnvironment($key, $value)
    {
        `cf set-env {$this->appName} {$key} {$value}`;
    }

    protected function restage()
    {
        if ($this->restage) {
            `cf restage {$this->appName}`;
        }
    }
}
