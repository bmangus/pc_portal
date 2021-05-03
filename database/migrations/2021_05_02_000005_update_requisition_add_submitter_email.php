<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRequisitionAddSubmitterEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('b_t_requisitions_22')){
            Schema::table('b_t_requisitions_22', function(Blueprint $table){
                $table->string('SubmitterEmail')->nullable();
            });
        }
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('b_t_requisitions_22')){
            Schema::table('b_t_requisitions_22', function(Blueprint $table){
                $table->dropColumn('SubmitterEmail');
            });
        }
    }
}
