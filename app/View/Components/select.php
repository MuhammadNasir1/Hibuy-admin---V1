<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class select extends Component
{
    /**
     * Create a new component instance.
     */

    public $label;
    public $name;
    public $id;
    public function __construct($label,  $name, $id)
    {
        $this->label = $label;
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select');
    }
}
