<?php

namespace App\View\Components;


class Country extends FormField
{
    /**
     * @var array
     */
    public $countries = [];

    public function __construct($field = null, $label = 'Option', $value = null, $hint = false, $selected = null,
                                $options = [], $labelExtra = null, $mandatory = true, $characterLimit = false,
                                $fullWidth = false, $autocomplete = false, $hideLabel = false)
    {
        parent::__construct($field, $label, $value, $hint, $selected, $options, $labelExtra,
            $mandatory, $characterLimit, $fullWidth, $autocomplete, $hideLabel);

        $jsonFile = public_path('assets/data/location-autocomplete-canonical-list.json');
        $data = json_decode(file_get_contents($jsonFile));
        $countries = [];

        foreach ($data as $item) {
            $iso = explode('-', explode(':', $item[1])[1]);

            array_push($countries, [
                'country' => $item[0],
                'iso' => end($iso),
            ]);
        }

        $this->countries = $countries;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.country');
    }
}
