<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="{{asset('/css/app.css')}}" media="all" />
    </head>
    <body>
        <div class="font-sans">
            <div class="flex w-full mb-3">
                <div class="w-1/6 border-black">
                    <img src="{{asset('/img/pc_logo.png')}}" class="mx-auto object-scale-down h-24">
                </div>
                <div class="w-5/6 ml-4">
                    <p class="font-extrabold">PUTNAM CITY PUBLIC SCHOOLS ACCOUNTS PAYABLE</p>
                    <div class="row ml-0">
                        <div class="w-1/2">
                            <p>5401 NW 40th  - Oklahoma City, OK 73122</p>
                            <p>Phone:(405) 495-5200  Fax (405) 495-8648</p>
                            <p class="">FINAL APPROVAL MUST BE OBTAINED PRIOR TO PURCHASE</p>
                        </div>
                        <div class="w-1/2">
                            <p><strong>Requisition Date:</strong> <span class="req_date req_data">TEST</span></p>
                            <p><strong>Purchase No.:</strong> <span class="ponum req_data" style="text-align: right;width: 105px">{{$po->PONumber}}</span></p>
                            <p><strong>Created By:</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex w-full">
                <div class="w-2/5 col">
                    <div class="m-2 p-2 border-2 border-black">
                        <div class="flex w-full mb-3">
                            <p class="object-left">Vendor Information:</p>
                            <p class="mr-2 ml-auto">Vendor #: <span class="ven_num req_data">{{$po->VendorID}}</span></p>
                        </div>
                        <div class="flex w-full">
                            <span class="vendor_info req_data">{{$po->Vendor}}<br>{{$po->VendorAddress}}<br>{{$po->VendorCity}}, {{$po->VendorState}} {{$po->VendorZip}}</span>
                            <p class="mr-2 ml-auto">
                                <span>W9</span>
                                <br>
                                <input type="checkbox" disabled="">
                                <br>
                                <span>FD</span>
                                <br>
                                <input type="checkbox" disabled="">
                            </p>
                        </div>
                        <div class="flex w-full">
                            <p>
                                <label>Phone</label>
                                <span class="ven_phone req_data">{{$po->VendorPhone}}</span><br>
                                <label>Fax</label>
                                <span class="ven_fax req_data"></span>
                            </p>
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
                            <span class="mx-auto">Bill To:</span>
                        </div>
                        <div class="w-5/6">
                            <span class="text-left">{{$po->BillToAttention}}<br>{{$po->BillToName}}<br>{{$po->BillToAddress}}<br>{{$po->BillToCity}}, {{$po->BillToState}} {{$po->BillToZip}}<br></span>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">1st</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{$po->ApprovedStatus1}}</div>
                                <div class="w-1/3">{{$po->ApprovedBy1}}</div>
                                <div class="w-1/3">{{$po->ApprovedDate1}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">2nd</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{$po->ApprovedStatus2}}</div>
                                <div class="w-1/3">{{$po->ApprovedBy2}}</div>
                                <div class="w-1/3">{{$po->ApprovedDate2}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">3rd</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{$po->ApprovedStatus3}}</div>
                                <div class="w-1/3">{{$po->ApprovedBy3}}</div>
                                <div class="w-1/3">{{$po->ApprovedDate3}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">4th</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{$po->ApprovedStatus4}}</div>
                                <div class="w-1/3">{{$po->ApprovedBy4}}</div>
                                <div class="w-1/3">{{$po->ApprovedDate4}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">5th</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{$po->ApprovedStatus5}}</div>
                                <div class="w-1/3">{{$po->ApprovedBy5}}</div>
                                <div class="w-1/3">{{$po->ApprovedDate5}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">TE</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{$po->ApprovedStatusTE}}</div>
                                <div class="w-1/3">{{$po->ApprovedByTE}}</div>
                                <div class="w-1/3">{{$po->ApprovedDateTE}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">Final</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{$po->FinalApprovedStatus}}</div>
                                <div class="w-1/3">{{$po->FinalApprovedBy}}</div>
                                <div class="w-1/3">{{$po->FinalApprovedDate}}</div>
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
