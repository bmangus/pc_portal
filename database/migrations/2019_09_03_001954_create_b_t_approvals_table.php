<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBTApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_t_approvals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('ApproverId')->nullable();
            $table->integer('ApprovedById')->nullable();
            $table->integer('RequisitionId')->nullable();
            $table->integer('Sequence')->default(0);
            $table->string('ApprovalStatus')->nullable();
            $table->dateTime('StatusSetAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b_t_approvals');
    }
}
