<?php

namespace App\View\Components;

class Textfield extends FormField
{
    /**
     * @var bool|mixed
     */
    public $spellcheck = true;

    /**
     * @var bool|mixed
     */
    public $maxlength = 120;

    /**
     * @var string
     */
    public $type = 'text';

    public function __construct($field = null, $label = 'Option', $value = null, $hint = false, $selected = null,
                                $options = [], $labelExtra = null, $mandatory = true, $characterLimit = false,
                                $fullWidth = false, $autocomplete = false, $hideLabel = false, $spellcheck = true,
                                $maxlength = 120, $type = 'text')
    {
        parent::__construct($field, $label, $value, $hint, $selected, $options, $labelExtra, $mandatory, $characterLimit, $fullWidth, $autocomplete, $hideLabel);

        $this->spellcheck = $spellcheck;
        $this->maxlength = $maxlength;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.textfield');
    }
}
