<?php

namespace App\Services;

use Hyyppa\FluentFM\Connection\FluentFMRepository;

class ATWorkflowService extends FluentFMRepository
{
    public function __construct()
    {
        parent::__construct([
            'file' => config('app.at_file'),
            'host' => config('app.at_host'),
            'user' => config('app.at_username'),
            'pass' => config('app.at_password'),
        ]);
    }
}
