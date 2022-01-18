<?php

namespace App\View\Components;

class TextArea extends FormField
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.text-area');
    }
}
