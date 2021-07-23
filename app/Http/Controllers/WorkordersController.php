<?php

namespace App\Http\Controllers;

use Hyyppa\FluentFM\Connection\FluentFMRepository;
use App\WorkOrders;
use Illuminate\Http\Request;

class WorkordersController extends Controller
{
    public $fmWorkOrders;
    public $index;
    public $limit;

    public function fwoIndex()
    {
        $this->canAccess();
        $user = strtolower(auth()->user()->uid);

        //dd($user);
        //$user = 'ahoggatt';

        //$this->handle();
        return view('workorders-base', compact('user'));
    }

    private function canAccess($bypass = false)
    {
        if($bypass) return $this;
        $groups = json_decode(auth()->user()->groups);
        if(!in_array('workflow_users', $groups) && !in_array('DOTPCAdmin', $groups)) {
            return abort(403, "You are not authorized to view the workorders site.");
        }
        return $this;
    }

    public function handle()
    {
        $fwoFM = new FluentFMRepository([
            'file' => config('app.fwo_file'),
            'host' => config('app.fwo_host'),
            'user' => config('app.fwo_username'),
            'pass' => config('app.fwo_password'),
            'client' => [
                'verify'=>false
            ],
        ]);

        $twoFM = new FluentFMRepository([
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
            ->getWorkOrders($fwoFM, 'facilities')
            ->updateWorkOrders()
            ->resetWorkOrders()
            ->resetIndex()
            ->getWorkOrders($twoFM, 'technology')
            ->updateWorkOrders()
            ->resetWorkOrders();

        return response('done');
    }

    public function getWorkOrders($connection, $system)
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
                    $this->fmWorkOrders[] = array_merge(['_fmid' => $key, '_fm_system' => $system], $item);
                }
            }
            $this->incrementIndex();
        }
        return $this;
    }

    public function updateWorkOrders()
    {
        foreach($this->fmWorkOrders as $wo) {
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

    private function resetWorkOrders()
    {
        $this->fmWorkOrders = [];
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
