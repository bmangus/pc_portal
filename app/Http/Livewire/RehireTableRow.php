<?php

namespace App\Http\Livewire;

use Hyyppa\FluentFM\Connection\FluentFMRepository;
use Livewire\Component;

class RehireTableRow extends Component
{
    public $rowId;
    public $name;
    public $subject;
    public $site;
    public $rehire;
    public $comments;
    public $ocas = "N/A";
    public $type = "N/A";
    public $category = "N/A";
    public $saved = true;
    public $errored = false;
    public $errorMsg = '';

    public function updated()
    {
        $this->saved = false;
    }

    public function save()
    {
        $this->errored = false;
        
        $fm = new FluentFMRepository([
            'file' => config('app.hr_rehire_file'),
            'host' => config('app.hr_rehire_host'),
            'user' => config('app.hr_rehire_username'),
            'pass' => config('app.hr_rehire_password'),
        ]);

        $data = ['Rehire'=>$this->rehire, 'Comments'=>$this->comments, 'RehireTimestamp'=>now()->format('m/d/Y g:i:s A')];
        //dd($data);
        try {
            $fm->update('web-staff', $data)
                ->where('id', $this->rowId)
                ->limit(1)
                ->exec();
        } catch(\Hyyppa\FluentFM\Exception\FilemakerException $e){
            $this->errored = true;
            $this->errorMsg = $e->getMessage();
        }

        if(!$this->errored){
            $this->saved = true;
        }
    }
    public function render()
    {
        return view('livewire.rehire-table-row');
    }
}
