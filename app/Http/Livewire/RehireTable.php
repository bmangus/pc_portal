<?php

namespace App\Http\Livewire;

use Hyyppa\FluentFM\Connection\FluentFMRepository;
use Livewire\Component;
use App\Services\HRStaffRehireService;

class RehireTable extends Component
{
    protected $fm;
    public $user;
    public $site;
    public $staff;
    public $schoolMap;
    public $schoolNames;
    public $showEditModal = false;

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

        $this->fm = new FluentFMRepository([
            'file' => config('app.hr_rehire_file'),
            'host' => config('app.hr_rehire_host'),
            'user' => config('app.hr_rehire_username'),
            'pass' => config('app.hr_rehire_password'),
        ]);

        try{
            $this->site = $this->fm->find('Sites')->where('ADGroup', $this->user)->get();
        } catch (\Exception $e){
            $this->site = null;
        }

        if($this->user === "bmangus" || $this->user === 'cbarber'){
            $this->staff = $this->fm->records('Staff')->limit(10)->get();
        } else if($this->site) {
            $this->staff = [];
            foreach($this->site as $site){
                try{
                    collect($this->fm->find('Staff')->where('SiteNo', $site['SiteNo'])->limit(1000)->get())->each(function($s){
                        $this->staff[] = $s;
                    });
                } catch(\Exception $e){
                    null;
                }
            }
        } else {
            return abort('403', 'Unauthorized - You are not specified as the site administrator for any sites.');
        }

        return view('livewire.rehire-table');
    }
}
