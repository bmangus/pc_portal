<?php

namespace App\Services;

use Hyyppa\FluentFM\Connection\FluentFMRepository;

class BTWorkflowService extends FluentFMRepository
{
    public function __construct()
    {
        parent::__construct([
            'file' => config('app.bt_file'),
            'host' => config('app.bt_host'),
            'user' => config('app.bt_username'),
            'pass' => config('app.bt_password'),
        ]);
    }
}
