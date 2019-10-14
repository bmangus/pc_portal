<?php

namespace Tests\Unit;

use App\Jobs\SyncBudgetTrackerJob;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function testBTSyncJob()
    {
        SyncBudgetTrackerJob::dispatchNow();
    }
}
