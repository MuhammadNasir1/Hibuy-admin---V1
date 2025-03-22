<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
    public $label;
    public $placeholder;
    public $name;
    public $id;
    public $required;
    public $value;

    public function __construct($label, $placeholder, $name, $id, $required, $value = null)
    {
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->name = $name;
        $this->id = $id;
        $this->required = $required;
        $this->value = $value;
    }

    public function render(): View|Closure|string
    {
        return view('components.textarea');
    }
}
