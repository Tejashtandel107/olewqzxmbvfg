<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public $photo;
    public $message;
    public $dismissable;
    public $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($photo="",$message="", $dismissable="",$type="success")
    {
        $this->photo = $photo;
        $this->message = $message;
        $this->dismissable = $dismissable;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.alert');
    }
}
