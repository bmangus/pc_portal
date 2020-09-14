<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAtRequisitionItemsFloats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('a_t_requisition_items')) {
            Schema::table('a_t_requisition_items', function (Blueprint $table) {
                $table->float('Total', 20, 2)->change();
                $table->float('unitPrice', 20, 2)->change();
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
