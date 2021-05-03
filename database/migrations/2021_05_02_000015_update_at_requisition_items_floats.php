<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAtRequisitionItemsFloats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('a_t_requisition_items_22')){
            Schema::table('a_t_requisition_items_22', function(Blueprint $table){
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
