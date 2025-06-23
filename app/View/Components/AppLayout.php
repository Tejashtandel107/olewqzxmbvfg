<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Page Title.
     *
     * @var string
     */
    public $pagetitle;
 
    /**
     * Breadcrumbs.
     *
     * @var Array
     */
    public $breadcrumbs;
 
    /**
     * Create the component instance.
     *
     * @param  string  $pagetitle
     * @param  string  $breadcrumbs
     * @return void
     */
    public function __construct($pagetitle, $breadcrumbs)
    {
        $this->pagetitle = $pagetitle;
        $this->breadcrumbs = $breadcrumbs;
    }    
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
