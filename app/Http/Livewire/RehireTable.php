<?php

namespace App\Http\Livewire;

use Hyyppa\FluentFM\Connection\FluentFMRepository;
use Livewire\Component;
use App\Services\HRStaffRehireService;

class RehireTable extends Component
{
    protected $fm;
    public $user;
    public $sites;
    public $staff;
    public $totalCount = 0;
    public $schoolMap;
    public $schoolNames;
    public $showEditModal = false;

    protected $listeners = ['reload' => '$refresh'];

    public function alert()
    {
        $this->dispatchBrowserEvent('alert', ['type'=>'error', 'message'=>'You do not have permission to edit at this time.']);
    }

    public function edit()
    {
        $this->showEditModal = true;
    }

    public function render()
    {
        return view('livewire.rehire-table');
    }
}
