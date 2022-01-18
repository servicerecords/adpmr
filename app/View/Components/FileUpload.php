<?php

namespace App\View\Components;

class FileUpload extends FormField
{
    public $accept;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.file-upload');
    }
}
