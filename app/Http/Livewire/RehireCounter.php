<?php

namespace App\Http\Livewire;

use Hyyppa\FluentFM\Connection\FluentFMRepository;
use Livewire\Component;

class RehireCounter extends Component
{
    public $totalCount;
    public $sites;

    public $listeners= ['getCounts','decrementCount'];

    public function mount()
    {
        $this->getCounts();
    }

    public function getCounts()
    {
        $this->totalCount = 0;
        $fm = new FluentFMRepository([
            'file' => config('app.hr_rehire_file'),
            'host' => config('app.hr_rehire_host'),
            'user' => config('app.hr_rehire_username'),
            'pass' => config('app.hr_rehire_password'),
        ]);
        foreach($this->sites as $site){
            try{
                $this->totalCount = $this->totalCount + collect($fm->find('web-staff')->where('SiteNo', $site['SiteNo'])->whereEmpty('Rehire')->limit(1000)->get())->count();
            } catch (\Exception $e){
                null;
            }
        }
    }

    public function decrementCount()
    {
        $this->totalCount--;
    }

    public function render()
    {
        return view('livewire.rehire-counter');
    }
}
