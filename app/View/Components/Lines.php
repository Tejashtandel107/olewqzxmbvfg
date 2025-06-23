<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Lines extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct(
        public $lines,
    ) {}


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.lines');
    }
}
