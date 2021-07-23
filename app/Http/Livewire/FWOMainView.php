<?php

namespace App\Http\Livewire;

use Hyyppa\FluentFM\Connection\FluentFMRepository;
use Illuminate\Support\Carbon;
use Livewire\Component;

class FWOMainView extends Component
{
    protected $fm;
    public $myWorkOrders;
    public $viewWo;
    public $pageBy = 10;
    public $pageNumber = 1;

    public function mount()
    {

    }


    public function render()
    {
        return view('livewire.f-w-o-main-view');
    }
}
