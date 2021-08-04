<?php

namespace App\Services;

class CountryService
{
    /**
     * @var CountryService
     */
    private static $instance = null;

    protected $country_data;

    private function __construct()
    {
        $this->country_data = json_decode(
            file_get_contents(
                public_path('assets/data/location-autocomplete-canonical-list.json')
            )
        );
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new CountryService();
        }

        return self::$instance;
    }

    public function getCode(string $country): string
    {
        $code = '';
        foreach ($this->country_data as $item) {
            if (strtolower($item[0]) == strtolower($country)) {
                list($type, $code) = explode(':', $item[1]);

                if ($type == 'territory')
                    $code = substr($code, 0, 2);

                break;
            }
        }

        return $code;
    }
}
