<?php

namespace App\Http\Livewire;

use Hyyppa\FluentFM\Connection\FluentFMRepository;
use Illuminate\Support\Carbon;
use Livewire\Component;

class RehireGroup extends Component
{
    protected $fm;
    public $sites;
    public $user;
    public $staff;
    public $totalCount = 0;


    public function mount()
    {
        $this->fm = new FluentFMRepository([
            'file' => config('app.hr_rehire_file'),
            'host' => config('app.hr_rehire_host'),
            'user' => config('app.hr_rehire_username'),
            'pass' => config('app.hr_rehire_password'),
        ]);

        if($this->user === "bmangus" || $this->user === 'cbarber') {
            $this->user = 'cboggs';
            $this->staff = $this->fm->records('Staff')->limit(10)->get();
        }

        try{
            $this->sites = $this->fm->find('Sites')->where('ADGroup', $this->user)->get();
        } catch (\Exception $e){
            $this->sites = null;
        }

        if($this->sites) {
            $this->staff = [];
            foreach($this->sites as $site){
                if(Carbon::parse($site['EndDate']) >= now()){
                    try{
                        collect($this->fm->find('Staff')->where('SiteNo', $site['SiteNo'])->limit(1000)->get())->each(function($s,$key){
                            //dd($s);
                            $this->staff[$s['Type']][$key] = $s;
                        });
                    } catch(\Exception $e){
                        null;
                    }
                }

            }
        } else {
            return abort('403', 'Unauthorized - You are not specified as the site administrator for any sites.');
        }
    }

    public function render()
    {
        return view('livewire.rehire-group');
    }
}
