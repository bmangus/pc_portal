<?php

namespace Tests\Unit;

use App\Jobs\SyncBudgetTrackerJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
