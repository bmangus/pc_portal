<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('_fmid')->nullable();
            $table->string('_fm_system')->nullable();
            $table->string('SubmittedBy')->nullable();
            $table->string('Contact')->nullable();
            $table->string('Location')->nullable();
            $table->text('Room')->nullable();
            $table->string('EmailOption')->nullable();
            $table->string('zd_OrderNumber')->nullable();
            $table->string('SubmitterEmail')->nullable();
            $table->text('Problem')->nullable();
            $table->string('SubmitDate')->nullable();
            $table->string('Completed')->nullable();
            $table->string('EmailManager')->nullable();
            $table->text('Resolution')->nullable();
            $table->string('AssignedDate')->nullable();
            $table->string('SiteNo')->nullable();
            $table->longText('HistoryListFA')->nullable();
            $table->string('AssignTo')->nullable();
            $table->string('zd_Dept')->nullable();
            $table->string('Email')->nullable();
            $table->string('AutoSiteNumber')->nullable();
            $table->string('Equipment')->nullable();
            $table->string('FixedAsset')->nullable();
            $table->string('SerialNo')->nullable();
            $table->string('Model')->nullable();
            $table->string('New')->nullable();
            $table->string('Product')->nullable();
            $table->string('RequestType')->nullable();
            $table->string('zd_CreatedByLogin')->nullable();
            $table->string('zd_OrderNo')->nullable();
            $table->string('OrderNo')->nullable();
            $table->string('OrderType')->nullable();
            $table->longText('HistoryListB_G')->nullable();
            $table->text('Feedback')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_orders');
    }
}
