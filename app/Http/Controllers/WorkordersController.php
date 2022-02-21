<?php

namespace App\Http\Controllers;

use Hyyppa\FluentFM\Connection\FluentFMRepository;
use App\WorkOrders;
use App\Jobs\SyncWorkOrdersJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;

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

    public function sync()
    {
        Queue::push(new SyncWorkOrdersJob());
        return response('done');
    }

}
