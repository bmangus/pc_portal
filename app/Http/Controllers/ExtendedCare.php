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
            'file'=>config('app.ec_file', 'SomeFile'),
            'host'=>config('app.ec_host', 'somewhere.com'),
            'user'=>config('app.ec_username','user'),
            'pass'=>config('app.ec_password', 'password')
        ]);
    }

    public function index()
    {
        try{
            $sites = collect($this->fm->find('web_sites')->where('Enabled', 'Yes')->get());
        } catch (\Exception $e) {
            return abort('503', 'Sorry, there are currently no sites available for extended care registration at this time.');
        }

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
