<?php

namespace App\Services;

use Hyyppa\FluentFM\Connection\FluentFMRepository;

class BTWorkflowService extends FluentFMRepository
{
    public function __construct()
    {
        parent::__construct([
            'file' => env('BT_FM_FILE', 'SomeFileSomewhere'),
            'host' => env('BT_FM_HOST', '127.1.2.1'),
            'user' => env('BT_FM_USERNAME', 'user'),
            'pass' => env('BT_FM_PASSWORD', 'password'),
        ]);
    }
}
