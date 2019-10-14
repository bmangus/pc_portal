<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBtApproversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bt_approvers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('ProjectCode');
            $table->integer('SiteNo');
            $table->string('Approver1')->nullable();
            $table->string('Approver1Email')->nullable();
            $table->string('Approver1FullName')->nullable();
            $table->string('Approver2')->nullable();
            $table->string('Approver2Email')->nullable();
            $table->string('Approver2FullName')->nullable();
            $table->string('Approver3')->nullable();
            $table->string('Approver3Email')->nullable();
            $table->string('Approver3FullName')->nullable();
            $table->string('Approver4')->nullable();
            $table->string('Approver4Email')->nullable();
            $table->string('Approver4FullName')->nullable();
            $table->string('Approver5')->nullable();
            $table->string('Approver5Email')->nullable();
            $table->string('Approver5FullName')->nullable();
            $table->string('ApproverFinal')->nullable();
            $table->string('ApproverFinalEmail')->nullable();
            $table->string('ApproverFinalName')->nullable();
            $table->string('ApproverTE')->nullable();
            $table->string('ApproverTEEmail')->nullable();
            $table->string('ApproverTEFullName')->nullable();
            $table->integer('fmId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bt_approvers');
    }
}
