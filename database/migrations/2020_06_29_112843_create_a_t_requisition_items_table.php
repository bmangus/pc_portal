<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateATRequisitionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_t_requisition_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('Access')->nullable();
            $table->string('AccountDesc')->nullable();
            $table->string('CatalogNo')->nullable();
            $table->string('Description')->nullable();
            $table->integer('Function')->nullable();
            $table->integer('Fund')->nullable();
            $table->integer('Job')->nullable();
            $table->integer('Object')->nullable();
            $table->integer('Program')->nullable();
            $table->integer('Project')->nullable();
            $table->integer('Subject')->nullable();
            $table->float('unitPrice')->nullable();
            $table->float('Total')->nullable();
            $table->integer('QtyAmt')->nullable();
            $table->integer('Qty')->nullable();
            $table->string('fmId')->nullable();
            $table->integer('RecID')->nullable();
            $table->text('RequisitionNo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('a_t_requisition_items');
    }
}
