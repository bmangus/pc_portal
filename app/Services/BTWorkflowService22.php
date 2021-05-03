<?php

namespace App\Services;

use Hyyppa\FluentFM\Connection\FluentFMRepository;

class BTWorkflowService22 extends FluentFMRepository
{
    public function __construct()
    {
        parent::__construct([
            'file' => config('app.bt_file_22'),
            'host' => config('app.bt_host_22'),
            'user' => config('app.bt_username_22'),
            'pass' => config('app.bt_password_22'),
        ]);
    }
}
