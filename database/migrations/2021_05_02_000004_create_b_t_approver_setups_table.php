<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBTApproverSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_t_approver_setups_22', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Approver');
            $table->string('ApproverEmail');
            $table->string('ApproverFName');
            $table->string('ApproverLName');
            $table->string('ReceiveEmails');
            $table->string('SuperUser');
            $table->integer('fm_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b_t_approver_setups_22');
    }
}
