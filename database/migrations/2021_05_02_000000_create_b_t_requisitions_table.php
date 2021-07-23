<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBTRequisitions22Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_t_requisitions_22', function (Blueprint $table) {
            $table->bigIncrements('pk');
            $table->timestamps();
            $table->string('id')->nullable();
            $table->string('GrandTotal')->nullable();
            $table->string('PONumber')->nullable();
            $table->string('RequisitionNo')->nullable();
            $table->integer('Project')->nullable();
            $table->string('ProjectFundSite')->nullable();
            $table->string('ProjectAsText')->nullable();
            $table->string('FiscalYear')->nullable();
            $table->string('Closed')->nullable();
            $table->string('Rejected')->nullable();
            $table->string('Submitted')->nullable();
            $table->string('Web_Status')->nullable();
            $table->string('ApprovalStatus')->nullable();
            $table->string('Status')->nullable();
            $table->string('Fax')->nullable();
            $table->string('gVendorName')->nullable();
            $table->string('Vendor')->nullable();
            $table->string('VendorEmail')->nullable();
            $table->string('VendorID')->nullable();
            $table->string('VendorInfo')->nullable();
            $table->string('VendorPhone')->nullable();
            $table->string('gShipTo')->nullable();
            $table->string('ShipFax')->nullable();
            $table->string('ShippingAddress')->nullable();
            $table->string('ShippingAddress2')->nullable();
            $table->string('ShippingCity')->nullable();
            $table->string('ShippingCompany')->nullable();
            $table->string('ShippingInfo')->nullable();
            $table->string('ShippingState')->nullable();
            $table->string('ShippingZip')->nullable();
            $table->string('ShippingZipPlus')->nullable();
            $table->string('ShipToPhone')->nullable();
            $table->string('ShipToSite')->nullable();
            $table->string('Function')->nullable();
            $table->string('Fund')->nullable();
            $table->string('FY')->nullable();
            $table->string('Object')->nullable();
            $table->string('BillToSite')->nullable();
            $table->string('ClosedStatus')->nullable();
            $table->string('Date')->nullable();
            $table->string('OCASSiteNo')->nullable();
            $table->string('Site')->nullable();
            $table->string('Web_School')->nullable();
            $table->string('BillToAddress')->nullable();
            $table->string('BillToAddress2')->nullable();
            $table->string('BillToAttention')->nullable();
            $table->string('BillToCity')->nullable();
            $table->string('BillToInfo')->nullable();
            $table->string('BillToName')->nullable();
            $table->string('BillToState')->nullable();
            $table->string('BillToZip')->nullable();
            $table->string('BillToZipPlus')->nullable();
            $table->string('Address1')->nullable();
            $table->string('Address2')->nullable();
            $table->string('ChargeTo')->nullable();
            $table->string('Technology')->nullable();
            $table->text('AccountCode')->nullable();
            $table->text('Web_BillInfo')->nullable();
            $table->text('Web_Comments')->nullable();
            $table->text('Web_ShippingInfo')->nullable();
            $table->text('Instructions')->nullable();
            $table->text('Comments')->nullable();
            $table->text('SignatureList')->nullable();
            $table->text('SignatureCollected')->nullable();
            $table->text('JobClass')->nullable();
            $table->text('Subject')->nullable();
            $table->text('Created')->nullable();
            $table->text('Modified')->nullable();
            $table->text('CreatedBy')->nullable();
            $table->text('VendorAddress')->nullable();
            $table->text('VendorCity')->nullable();
            $table->text('VendorState')->nullable();
            $table->text('VendorZip')->nullable();
            $table->text('Attn')->nullable();
            $table->string('RecID')->nullable();
            $table->text('zg_recid')->nullable();
            $table->string('Program')->nullable();
            $table->text('_Closed')->nullable();
            $table->text('ApprovedBy1')->nullable();
            $table->text('ApprovedBy2')->nullable();
            $table->text('ApprovedBy3')->nullable();
            $table->text('ApprovedBy4')->nullable();
            $table->text('ApprovedBy5')->nullable();
            $table->text('ApprovedStatus1')->nullable();
            $table->text('ApprovedStatus2')->nullable();
            $table->text('Approvedstatus3')->nullable();
            $table->text('ApprovedStatus4')->nullable();
            $table->text('ApprovedStatus5')->nullable();
            $table->text('ApprovedDate1')->nullable();
            $table->text('ApprovedDate2')->nullable();
            $table->text('ApprovedDate3')->nullable();
            $table->text('ApprovedDate4')->nullable();
            $table->text('ApprovedDate5')->nullable();
            $table->text('ApprovedComments1')->nullable();
            $table->text('ApprovedComments2')->nullable();
            $table->text('ApprovedComments3')->nullable();
            $table->text('ApprovedComments4')->nullable();
            $table->text('ApprovedComments5')->nullable();
            $table->text('ApprovedByTE')->nullable();
            $table->text('ApprovedStatusTE')->nullable();
            $table->text('ApprovedDateTE')->nullable();
            $table->text('ApprovedCommentsTE')->nullable();
            $table->text('FinalApprovalFonda')->nullable();
            $table->text('FinalApprovedBy')->nullable();
            $table->text('FinalApprovedComments')->nullable();
            $table->text('FinalApprovedDate')->nullable();
            $table->text('FinalApprovedStatus')->nullable();
            $table->text('FinalApprovedStatus6Rejected')->nullable();
            $table->text('submissionLog')->nullable();
            $table->text('SignatureLCollected')->nullable();
            $table->text('lvl_lastSyncDateTime')->nullable();
            $table->text('zd_ModifiedDateTime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b_t_requisitions_22');
    }
}
