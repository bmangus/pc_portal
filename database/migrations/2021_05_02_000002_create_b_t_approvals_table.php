<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBTApprovals22Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_t_approvals_22', function (Blueprint $table) {
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
        Schema::dropIfExists('b_t_approvals_22');
    }
}
