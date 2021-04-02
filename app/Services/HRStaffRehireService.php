<?php

namespace App\Services;

use Hyyppa\FluentFM\Connection\FluentFMRepository;

class HRStaffRehireService extends FluentFMRepository
{
    public function __construct()
    {
        parent::__construct([
            'file' => config('app.hr_rehire_file'),
            'host' => config('app.hr_rehire_host'),
            'user' => config('app.hr_rehire_username'),
            'pass' => config('app.hr_rehire_password'),
        ]);
    }
}