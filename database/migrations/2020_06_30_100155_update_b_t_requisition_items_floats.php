<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBTRequisitionItemsFloats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('b_t_requisition_items')) {
            Schema::table('b_t_requisition_items', function (Blueprint $table) {
                $table->float('Total', 20, 2)->change();
                $table->float('UnitPrice', 20, 2)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
