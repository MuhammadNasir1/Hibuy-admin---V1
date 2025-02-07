<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class textarea extends Component
{
    /**
     * Create a new component instance.
     */
    public $label;
    public $placeholder;
    public $name;
    public $type;
    public $id;
    public $required;

    public function __construct($label, $placeholder, $name, $type, $id ,$required)
    {
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->name = $name;
        $this->type = $type;
        $this->id = $id;
        $this->required = $required;

    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.textarea');
    }
}
