<?php

namespace App\Http\Controllers;

use Hyyppa\FluentFM\Connection\FluentFMRepository;
use Illuminate\Http\Request;
use App\Http\Requests\StoreExtendedCareRegistration;

class ExtendedCare extends Controller
{
    protected $fm;

    public function __construct()
    {
        $this->fm = new FluentFMRepository([
            'file'=>'ExtendedCareEnrollment',
            'host'=>'10.40.1.97',
            'user'=>'web_user',
            'pass'=>'ec_web_user'
        ]);
    }

    public function index()
    {
        $sites = $this->fm->records('web_sites')->sortAsc('SiteName')->get();
        return response()->view('extendedCare.index', compact('sites'));
    }

    public function submit(StoreExtendedCareRegistration $request)
    {
        $data = $request->validated();
        unset($data['_token']);
        $data['DateofBirth'] = \Carbon\Carbon::parse($data['DateofBirth'])->format('m/d/Y');
        $this->fm->create('Web_Roster', $data);
        return redirect()->to('https://www.putnamcityschools.org/schools/extended-care/payment');
    }
}
