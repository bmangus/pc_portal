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
        $sites = collect($this->fm->find('web_sites')->where('Enabled', 'Yes')->get());
        $sites = $sites->sort();
        return response()->view('extendedCare.index', compact('sites'));
    }

    public function submit(StoreExtendedCareRegistration $request)
    {
        $data = $request->validated();
        unset($data['_token']);
        $data['DateofBirth'] = \Carbon\Carbon::parse($data['DateofBirth'])->format('m/d/Y');
        $res = $this->fm->create('Web_Roster', $data);

        $account = $this->fm->record('Web_Roster', $res)->get()[$res]['AccountNo'];
        return response()->view('extendedCare.payment', compact('account'));
    }
}
