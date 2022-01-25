<?php

namespace App\View\Components;

class RadioGroup extends FormField
{
    /**
     * @var bool|mixed
     */
    public $hideLegend = false;

    /**
     * @var string
     */
    public $questionTag = 'h1';

    /**
     * @var bool|mixed
     */
    public $hasConditionals = false;

    public function __construct($field = null, $label = 'Option', $value = null, $hint = false, $selected = null,
                                $options = [], $labelExtra = null, $mandatory = true, $characterLimit = false,
                                $fullWidth = false, $autocomplete = false, $hideLabel = false, $hideLegend = false, $hasConditionals = false,
                                $questionTag = 'h1')
    {

        parent::__construct($field, $label, $value, $hint, $selected, $options,
            $labelExtra, $mandatory, $characterLimit, $fullWidth, $autocomplete, $hideLabel);

        $this->hideLegend = $hideLegend;
        $this->hasConditionals = $hasConditionals;
        $this->questionTag = $questionTag;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.radio-group');
    }
}
