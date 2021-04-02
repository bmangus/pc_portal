<?php

namespace App\Http\Livewire;

use Hyyppa\FluentFM\Connection\FluentFMRepository;
use Livewire\Component;
use App\Services\HRStaffRehireService;

class RehireTable extends Component
{
    protected $fm;
    public $user;
    public $staff;
    public $schoolMap;
    public $schoolNames;

    public function alert()
    {
        $this->dispatchBrowserEvent('alert', ['type'=>'error', 'message'=>'You do not have permission to edit at this time.']);
    }

    public function render()
    {

        $this->fm = new FluentFMRepository([
            'file' => config('app.hr_rehire_file'),
            'host' => config('app.hr_rehire_host'),
            'user' => config('app.hr_rehire_username'),
            'pass' => config('app.hr_rehire_password'),
        ]);

        if($this->user->uid === "bmangus" || $this->user->uid === 'cboggs' || $this->user->uid === 'cbarber'){
            $this->staff = $this->fm->records('Staff')->limit(5000)->get();
        } else {
            $this->staff = [];//$this->fm->find('Staff')->where('SiteNo', $school)->limit(1000)->get();
        }

        return view('livewire.rehire-table');
    }
}
