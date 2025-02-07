<?php

namespace App\View\Components;

use Illuminate\View\Component;

class modalbutton extends Component
{
    /**
     * Create a new component instance.
     */
    public $title;
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.modal-button');
    }
}
