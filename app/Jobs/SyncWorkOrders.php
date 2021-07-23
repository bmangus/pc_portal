<?php

namespace App\Jobs;

use App\WorkOrders;

use Egulias\EmailValidator\Exception\AtextAfterCFWS;
use Hyyppa\FluentFM\Connection\FluentFMRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SyncWorkOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fwoFMConnection;
    public $twoFMConnection;
    public $fwoWorkOrders;
    public $twoWorkOrders;
    public $index;
    public $limit;


    public function handle()
    {
        $this->fwoFMConnection = new FluentFMRepository([
            'file' => config('app.fwo_file'),
            'host' => config('app.fwo_host'),
            'user' => config('app.fwo_username'),
            'pass' => config('app.fwo_password'),
            'client' => [
                'verify'=>false
            ],
        ]);

        $this->twoFMConnection = new FluentFMRepository([
            'file' => config('app.two_file'),
            'host' => config('app.two_host'),
            'user' => config('app.two_username'),
            'pass' => config('app.two_password'),
            'client' => [
                'verify'=>false
            ],
        ]);

        $this->limit = 100;

        $this
            ->resetIndex()
            //->getFacilityWorkOrders()
            ->getWorkOrders($this->fwoFMConnection, $this->fwoWorkOrders)
            ->updateWorkOrders($this->fwoWorkOrders)
            ->resetIndex()
            //->getTechnologyWorkOrders()
            ->getWorkOrders($this->twoFMConnection, $this->twoWorkOrders)
            ->updateWorkOrders($this->twoWorkOrders)
            ->parseIntoSQL();
    }

    public function getFacilityWorkOrders()
    {
        $wo = 'something';
        while(!empty($wo)){
            try {
                if($this->index === 0){
                    $wo = $this->fwoFMConnection->find('Web')->whereEmpty('Completed')->limit($this->limit)->get();
                } else {
                    $wo = $this->fwoFMConnection->find('Web')->whereEmpty('Completed')->limit($this->limit)->offset($this->index)->get();
                }

            } catch(\Exception $e) {
                $wo = [];
            }
            if(!empty($wo)){
                foreach($wo as $key => $item){
                    $this->fwoWorkOrders[] = array_merge(['_fmid'=>$key, '_fm_system'=>'facilities'], $item);
                }
            }
            $this->incrementIndex();
        }
        return $this;
    }

    public function getTechnologyWorkOrders()
    {
        $wo = 'something';
        while(!empty($wo)){
            try {
                if($this->index === 0){
                    $wo = $this->twoFMConnection->find('Web')->whereEmpty('Completed')->limit($this->limit)->get();
                } else {
                    $wo = $this->twoFMConnection->find('Web')->whereEmpty('Completed')->limit($this->limit)->offset($this->index)->get();
                }

            } catch(\Exception $e) {
                $wo = [];
            }
            if(!empty($wo)){
                foreach($wo as $key => $item){
                    $this->twoWorkOrders[] = array_merge(['_fmid'=>$key, '_fm_system'=>'technology'], $item);
                }
            }
            $this->incrementIndex();
        }
        return $this;
    }

    public function getWorkOrders($connection, $workOrders)
    {
        $wo = 'something';
        while(!empty($wo)) {
            try {
                if ($this->index === 0) {
                    $wo = $connection->find('Web')->whereEmpty('Completed')->limit($this->limit)->get();
                } else {
                    $wo = $connection->find('Web')->whereEmpty('Completed')->limit($this->limit)->offset($this->index)->get();
                }

            } catch (\Exception $e) {
                $wo = [];
            }
            if (!empty($wo)) {
                foreach ($wo as $key => $item) {
                    $workOrders[] = array_merge(['_fmid' => $key, '_fm_system' => 'technology'], $item);
                }
            }
            $this->incrementIndex();
        }
        return $this;
    }

    public function updateWorkOrders($orders)
    {
        foreach($orders as $wo) {
            $this->updateTable($wo['_fmid'], $wo['_fm_system'], $wo);
        }
        return $this;
    }

    private function updateTable($id, $system, $record)
    {
        try{
            WorkOrders::updateOrCreate([
                '_fmid'=>$id,
                '_fm_system'=>$system
            ], $record);
        } catch(\Exception $e){
            Log::debug($e->getMessage());
        }
    }

    private function resetIndex()
    {
        $this->index = 0;
        return $this;
    }

    private function incrementIndex()
    {
        if($this->index === 0){
            $this->index = $this->limit + 1;
        } else {
            $this->index += $this->limit;
        }
        return $this;
    }

}

