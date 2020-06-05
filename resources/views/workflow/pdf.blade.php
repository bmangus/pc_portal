<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            .column {
                float: left;
                font-size: 10px;
                vertical-align: bottom;
            }

            .left{
                width: 20%;
            }

            .middle {
                width: 45%;
            }

            .right {
                width: 35%;
            }

            .col-2-1 {
                width: 50%;
                border: black;
                border-width: thin;
            }

            .col-2-2 {
                width: 50%;
                border: black;
                border-width: thin;
            }

            .mb-0{
                margin-bottom: 0px;
                padding-bottom: 0px;
            }

            .mb-1{
                margin-bottom: 1px;
            }

            .mb-2{
                margin-bottom: 2px;
            }
        </style>
    </head>
    <body style="display: grid;">
        <div style="width: 100%" style="font-size: 0.5em;">
            <div class="column left">
                <img src="{{public_path('/img/pc_logo.png')}}" style="width: 100px;">
            </div>
            <div class="column middle">

                <b>PUTNAM CITY PUBLIC SCHOOLS ACCOUNTS PAYABLE</b><br>
                5401 NW 40th  - Oklahoma City, OK 73122<br>
                Phone:(405) 495-5200  Fax (405) 495-8648<br>
                FINAL APPROVAL MUST BE OBTAINED PRIOR TO PURCHASE
            </div>
            <div class="column right">
               <b>Requisition Date:</b> <span class="req_date req_data">{{$po->Date}}</span><br>
                <b>Purchase No.:</b> {{$po->PONumber}}<br>
                <b>Created By:</b>
            </div>
        </div>
        <div style="width: 100%">
            <div class="col-1-1">
                <p style="float: left; width:50%">Vendor Information:</p>
                <p style="float:right; width: 50%">Vendor #: <span class="ven_num req_data">{{$po->VendorID}}</span></p>
                <span class="vendor_info req_data">{{$po->Vendor}}<br>
                    {{$po->VendorAddress}}<br>
                    {{$po->VendorCity}}, {{$po->VendorState}} {{$po->VendorZip}}</span>
                <div style="float:right;"><span>W9</span><br><input type="checkbox" disabled=""></div>
                <div style="float:right;"><span>FD</span><br><input type="checkbox" disabled=""></div>


                <p>
                    <label>Phone</label>
                    <span class="ven_phone req_data">{{$po->VendorPhone}}</span><br>
                    <label>Fax</label>
                    <span class="ven_fax req_data"></span>
                </p>
            </div>
            <div class="col-1-2">
                <span>Bill To:</span>
                <span >{{$po->BillToAttention}}<br>{{$po->BillToName}}<br>{{$po->BillToAddress}}<br>{{$po->BillToCity}}, {{$po->BillToState}} {{$po->BillToZip}}<br></span>
                <div>1st</div>
                <div>{{$po->ApprovedStatus1}}</div>
                <div>{{$po->ApprovedBy1}}</div>
                <div>{{$po->ApprovedDate1}}</div>
                <div>2nd</div>
                <div>{{$po->ApprovedStatus2}}</div>
                <div>{{$po->ApprovedBy2}}</div>
                <div>{{$po->ApprovedDate2}}</div>
                <div>3rd</div>
                <div>{{$po->ApprovedStatus3}}</div>
                <div>{{$po->ApprovedBy3}}</div>
                <div>{{$po->ApprovedDate3}}</div>
                <div>4th</div>
                <div>{{$po->ApprovedStatus4}}</div>
                <div>{{$po->ApprovedBy4}}</div>
                <div>{{$po->ApprovedDate4}}</div>
                <div>5th</div>
                <div>{{$po->ApprovedStatus5}}</div>
                <div>{{$po->ApprovedBy5}}</div>
                <div>{{$po->ApprovedDate5}}</div>
                <div>TE</div>
                <div>{{$po->ApprovedStatusTE}}</div>
                <div>{{$po->ApprovedByTE}}</div>
                <div>{{$po->ApprovedDateTE}}</div>
                <div>Final</div>
                <div>{{$po->FinalApprovedStatus}}</div>
                <div>{{$po->FinalApprovedBy}}</div>
                <div>{{$po->FinalApprovedDate}}</div>
            </div>
        </div>


                    <div class=" m-2 p-2 border-2 border-black">
                        <p class="bg-black text-white p-1 mb-3">Ship To:</p>
                        <span class="vendor_info req_data">{{$po->Attn}}<br>{{$po->ShippingCompany}}<br>{{$po->ShippingAddress}}<br>{{$po->ShippingCity}}, {{$po->ShippingState}} {{$po->ShippingZip}}</span>
                        <p class="clear_all"></p>
                        <p class="comm">
                            <label>Phone</label>
                            <span class="ship_phone req_data">{{$po->ShipToPhone}}</span><br>
                            <label>Fax</label>
                            <span class="ship_fax req_data">{{$po->ShipFax}}</span>
                        </p>
                    </div>
                </div>
                <div class="w-3/5 m-2 p-2 border-2 border-black">
                    <div class="flex w-full mb-4">
                        <div class="w-1/6 text-center">

                        </div>
                        <div class="w-5/6">
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">

                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex w-full mx-2 px-2">
                <span>Instructions</span>
            </div>
            <div class="flex w-full">
                <div class="w-full col">
                    <div class="m-2 p-2 border-2 border-black">
                        "($po->Instructions.length < 1) ? "No instructions available." : $po.Instructions"
                    </div>
                </div>
            </div>
            <div class="flex w-full">
                <div class="w-full col">
                    <div class="m-2">
                        <table class="table-fixed border-2 border-top-table border-black">
                            <tbody>
                            <tr>
                                <td class="w-1/6 border-2 border-black">FY</td>
                                <td class="w-1/12 border-2 border-black">Fund</td>
                                <td class="w-1/12 border-2 border-black">Project</td>
                                <td class="w-1/12 border-2 border-black">Function</td>
                                <td class="w-1/12 border-2 border-black">Program</td>
                                <td class="w-1/12 border-2 border-black">Subject</td>
                                <td class="w-1/12 border-2 border-black">Ship Site</td>
                                <td class="w-1/12 border-2 border-black">Site</td>
                                <td class="w-1/3 border-2 border-black">Encumbrance No.</td>
                            </tr>
                            <tr>
                                <td class="border-2 border-black">{{$po->FiscalYear}}</td>
                                <td class="border-2 border-black">{{$po->Fund}}</td>
                                <td class="border-2 border-black">{{$po->Project}}</td>
                                <td class="border-2 border-black">{{$po->Function}}</td>
                                <td class="border-2 border-black">{{$po->Program}}</td>
                                <td class="border-2 border-black">{{$po->Subject}}</td>
                                <td class="border-2 border-black">{{$po->JobClass}}</td>
                                <td class="border-2 border-black">{{$po->Site}}</td>
                                <td class="border-2 border-black">{{$po->PONumber}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="flex w-full">
                <div class="w-full col">
                    <div class="m-2">
                        <table class="table-fixed border-2 border-top-table border-black">
                            <tbody>
                            <tr>
                                <td class="w-1/12 border-2 border-black">Qty</td>
                                <td class="w-1/12 border-2 border-black">Fixed Asset</td>
                                <td class="w-1/12 border-2 border-black">Item No</td>
                                <td class="w-2/5 border-2 border-black">Item Description</td>
                                <td class="w-1/12 border-2 border-black">Object Code</td>
                                <td class="w-1/12 border-2 border-black">Unit price</td>
                                <td class="w-1/6 border-2 border-black">Total</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <p class="foot" style="font-size:14px">Charge To: {{$po->ChargeTo}}</p>
            <p>Comments Go Here.....</p>

        </div>

    </body>

</html>
