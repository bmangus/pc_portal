<?php

namespace App\Http\Controllers;

use Hyyppa\FluentFM\Connection\FluentFMRepository;
use Illuminate\Http\Request;

class ExtendedCare extends Controller
{
    public function index()
    {
        return response()->view('extendedCare.index');
    }

    public function submit(Request $request)
    {
        $fm = new FluentFMRepository([
           'file'=>'ExtendedCareEnrollment',
            'host'=>'10.40.1.97',
            'user'=>'web_user',
            'pass'=>'ec_web_user'
        ]);
        $data = $request->all();
        unset($data['_token']);
        $data['DateofBirth'] = \Carbon\Carbon::parse($data['DateofBirth'])->format('m/d/Y');
        $fm->create('Web_Roster', $data);
        return redirect()->back();
    }
}
