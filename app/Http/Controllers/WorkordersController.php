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

}
