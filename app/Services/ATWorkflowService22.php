<?php

namespace App\Services;

use Hyyppa\FluentFM\Connection\FluentFMRepository;

class ATWorkflowService22 extends FluentFMRepository
{
    public function __construct()
    {
        parent::__construct([
            'file' => config('app.at_file_22'),
            'host' => config('app.at_host_22'),
            'user' => config('app.at_username_22'),
            'pass' => config('app.at_password_22'),
        ]);
    }
}
