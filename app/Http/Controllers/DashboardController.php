<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $this->canAccess();
        $user = strtolower(auth()->user()->uid);
        //dd(auth()->user());
        //$user = 'ahoggatt';

        return view('dashboard-base', compact('user'));
    }

    private function canAccess($bypass = false)
    {
        if($bypass) return $this;
        $groups = json_decode(auth()->user()->groups);
        if(!in_array('workflow_users', $groups) && !in_array('DOTPCAdmin', $groups)) {
            return abort(403, "You are not authorized to view the rehire site.");
        }
        return $this;
    }

}
