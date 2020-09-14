<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBTRequisitionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_t_requisition_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('Access')->nullable();
            $table->string('AccountDesc')->nullable();
            $table->string('ActiveLogin')->nullable();
            $table->string('CatalogNo')->nullable();
            $table->string('ChargeTo')->nullable();
            $table->string('DeptAdminLogin')->nullable();
            $table->string('Description')->nullable();
            $table->string('FixedAsset')->nullable();
            $table->string('LoginDeptAdmin')->nullable();
            $table->string('Partial')->nullable();
            $table->string('SpecialProject')->nullable();
            $table->string('TE')->nullable();
            $table->string('Object')->nullable();
            $table->string('QtyAmt')->nullable();
            $table->integer('Fund')->nullable();
            $table->string('FundFunctionObject')->nullable();
            $table->string('IfSpecialProject')->nullable();
            $table->integer('RecID')->nullable();
            $table->string('SiteNo')->nullable();
            $table->integer('ItemCount')->nullable();
            $table->integer('ReqNo')->nullable();
            $table->float('UnitPrice')->nullable();
            $table->float('Total')->nullable();
            $table->string('TotalExpensesID')->nullable();
            $table->integer('zd_RequisRecID')->nullable();
            $table->integer('Project')->nullable();
            $table->integer('Qty')->nullable();
            $table->string('OverBudget')->nullable();
            $table->string('ObjectName')->nullable();
            $table->string('BillToInfo')->nullable();
            $table->string('ClosedPO')->nullable();
            $table->string('Codes')->nullable();
            $table->string('Date')->nullable();
            $table->string('Dept')->nullable();
            $table->string('FiscalYear')->nullable();
            $table->string('fmId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b_t_requisition_items');
    }
}
