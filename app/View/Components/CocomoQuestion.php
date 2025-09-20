<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CocomoQuestion extends Component
{
    public $name;
    public $label;
    public $options;
    public $description;

    public function __construct($name, $label, $options, $description = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        $this->description = $description;
    }

    public function render()
    {
        return view('components.cocomo-question');
    }
}