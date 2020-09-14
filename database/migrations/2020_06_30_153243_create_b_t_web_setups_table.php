<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBTWebSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_t_web_setups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('SubmitterEmail')->nullable();
            $table->string('SubmitterUserName')->nullable();
            $table->string('Approver')->nullable();
            $table->string('ApproverEmail')->nullable();
            $table->string('ApproverUsername')->nullable();
            $table->string('IsSuperUser')->nullable();
            $table->string('SiteNo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b_t_web_setups');
    }
}
