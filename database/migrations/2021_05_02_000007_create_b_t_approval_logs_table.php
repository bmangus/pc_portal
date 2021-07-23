<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBTApprovalLogs22Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bt_approval_logs_22', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('RecID')->nullable();
            $table->string('AuthenticatedUser')->nullable();
            $table->string('ImpersonatingAsUser')->nullable();
            $table->string('PONumber')->nullable();
            $table->string('CalculatedPosition')->nullable();
            $table->string('ApprovalStatus')->nullable();
            $table->boolean('EmailSent')->nullable();
            $table->string('Details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bt_approval_logs_22');
    }
}
