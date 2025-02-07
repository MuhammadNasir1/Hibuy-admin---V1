<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class input extends Component
{
    /**
     * Create a new component instance.
     */

    public $label;
    public $placeholder;
    public $name;
    public $type;
    public $id;

    public function __construct($label, $placeholder, $name, $type, $id)
    {
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->name = $name;
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input');
    }
}
