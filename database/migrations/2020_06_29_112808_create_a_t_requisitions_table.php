<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateATRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_t_requisitions', function (Blueprint $table) {
            $table->bigIncrements('pk');
            $table->string('Date')->nullable();
            $table->string('FiscalYear')->nullable();
            $table->string('VendorID')->nullable();
            $table->string('ProjectCode_Sub')->nullable();
            $table->string('a_ProjectName')->nullable();
            $table->string('ProjectSubName')->nullable();
            $table->string('RequestedBy')->nullable();
            $table->string('Custodian')->nullable();
            $table->string('Vendor')->nullable();
            $table->string('VendorAddress1')->nullable();
            $table->string('VendorAddress2')->nullable();
            $table->string('VendorCity')->nullable();
            $table->string('VendorState')->nullable();
            $table->string('VendorZip')->nullable();
            $table->string('VendorZipPlus')->nullable();
            $table->string('VendorPhone')->nullable();
            $table->string('VendorFax')->nullable();
            $table->string('VendorEmail')->nullable();
            $table->string('VendorAcctNo')->nullable();
            $table->string('Attn')->nullable();
            $table->string('DateOrdered')->nullable();
            $table->text('ShippingCompany')->nullable();
            $table->text('ShippingAddress')->nullable();
            $table->text('ShippingAddress2')->nullable();
            $table->text('ShippingCity')->nullable();
            $table->text('ShippingState')->nullable();
            $table->text('ShippingZip')->nullable();
            $table->text('ShippingZipPlus')->nullable();
            $table->text('ShipToPhone')->nullable();
            $table->text('OrderedVia')->nullable();
            $table->text('PONumber')->nullable();
            $table->text('Site')->nullable();
            $table->text('Note')->nullable();
            $table->text('RequisitionNo')->nullable();
            $table->text('Status')->nullable();
            $table->text('ApprovedBy1')->nullable();
            $table->text('ApprovedStatus1')->nullable();
            $table->text('ApprovedDate1')->nullable();
            $table->text('ApprovedComments1')->nullable();
            $table->text('ApprovedByTE')->nullable();
            $table->text('ApprovedStatusTE')->nullable();
            $table->text('ApprovedDateTE')->nullable();
            $table->text('ApprovedCommentsTE')->nullable();
            $table->text('SubmitterEmail')->nullable();
            $table->text('Technology')->nullable();
            $table->text('Approver')->nullable();
            $table->text('ApproverEmail')->nullable();
            $table->text('ApproverUsername')->nullable();
            $table->text('zd_ModifiedDateTime')->nullable();
            $table->text('lvl_LastSyncDateTime')->nullable();
            $table->text('zg_recid')->nullable();
            $table->string('RecID')->nullable();
            $table->string('GrandTotal')->nullable();
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
        Schema::dropIfExists('a_t_requisitions');
    }
}
