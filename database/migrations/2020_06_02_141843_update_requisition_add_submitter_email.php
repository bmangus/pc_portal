<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRequisitionAddSubmitterEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('b_t_requisitions')) {
            Schema::table('b_t_requisitions', function (Blueprint $table) {
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
        if (Schema::hasTable('b_t_requisitions')) {
            Schema::table('b_t_requisitions', function (Blueprint $table) {
                $table->dropColumn('SubmitterEmail');
            });
        }
    }
}
