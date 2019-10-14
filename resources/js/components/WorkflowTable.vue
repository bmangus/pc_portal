<template>
    <div class="w-full m-3">
        <div v-if="loading" class="flex inset-auto">
            <div class="loader">Loading...</div>
        </div>
        <div v-else>
            <t-table
                :headers="['PO Number', 'Vendor', 'Site', 'Project', 'Cost', 'Actions']"
                :data="requisitions"
                :responsive="true"
                :responsive-breakpoint="768">
                <template v-slot:row="props">
                    <tr :class="[props.trClass, props.rowIndex % 2 === 0 ? 'bg-gray-100' : '']">
                        <td :class="props.tdClass">
                            {{ props.row.PONumber }}
                        </td>
                        <td :class="props.tdClass">
                            {{props.row.Vendor}}
                        </td>
                        <td :class="props.tdClass">
                            {{props.row.ShippingCompany}}
                        </td>
                        <td :class="props.tdClass">
                            {{props.row.Project}}
                        </td>
                        <td :class="props.tdClass">
                            ${{parseFloat(props.row.GrandTotal).toFixed(2)}}
                        </td>
                        <td :class="props.tdClass">
                            <t-button size="sm" variant="primary" @click="$refs.modal.show()">Open</t-button>
                            <t-button size="sm" variant="secondary" >Forward</t-button>
                        </td>
                    </tr>
                </template>
            </t-table>
            <t-modal ref="modal">
                <div class="requisitions">
                <div class="header">
                    <img src="https://staff.putnamcityschools.org/.pc/site/assets/img/pc_main_logo.png" class="print_logo">
                    <p class="float_left" style="font-weight:bold;">PUTNAM CITY PUBLIC SCHOOLS ACCOUNTS PAYABLE</p>
                    <div class="col_left float_left">
                        <p>5401 NW 40th  - Oklahoma City, OK 73122</p>
                        <p>Phone:(405) 495-5200  Fax (405) 495-8648</p>
                    </div>
                    <div class="col_right float_right">
                        <p><strong>Requisition Date:</strong> <span class="req_date req_data">06/21/2019</span></p>
                        <p><strong>Purchase No.:</strong> <span class="ponum req_data" style="text-align: right;width: 105px">0-00177-096</span></p>
                        <p><span class="created_by req_data float_right">&nbsp;operations</span><strong>Created By:</strong></p>
                    </div>
                    <p class="header_final">FINAL APPROVAL MUST BE OBTAINED PRIOR TO PURCHASE</p>
                    <p class="clear_all"></p>
                </div>
                <div class="mid_header">

                    <div class="billing">
                        <span class="bill_to_label">Bill To:</span>
                        <span class="BillToInfo req_data">CINDY SMITH<br>Putnam City Operations<br>5401 NW 40th St<br>Oklahoma City, Ok 73122<br></span>
                        <p class="clear_all"></p>
                        <br><br><br>
                        <p class="signature"><span class="sig_label">1st</span>
                            <span class="ApprovedStatus1 req_data">Approved</span>
                            <span class="ApprovedBy1 req_data">Cecil Bowles</span>
                            <span class="ApprovedDate1 req_data">06/21/2019</span>
                        </p>
                        <p class="signature"><span class="sig_label">2nd</span>
                            <span class="ApprovedStatus2 req_data"></span>
                            <span class="ApprovedBy2 req_data"></span>
                            <span class="ApprovedDate2 req_data"></span>
                        </p>
                        <p class="signature"><span class="sig_label">3rd</span>
                            <span class="ApprovedStatus3 req_data"></span>
                            <span class="ApprovedBy3 req_data"></span>
                            <span class="ApprovedDate3 req_data"></span>
                        </p>
                        <p class="signature"><span class="sig_label">4th</span>
                            <span class="ApprovedStatus4 req_data"></span>
                            <span class="ApprovedBy4 req_data"></span>
                            <span class="ApprovedDate4 req_data"></span>
                        </p>
                        <p class="signature"><span class="sig_label">5th</span>
                            <span class="ApprovedStatus5 req_data"></span>
                            <span class="ApprovedBy5 req_data"></span>
                            <span class="ApprovedDate5 req_data"></span>
                        </p>
                        <p class="signature"><span class="sig_label">TE</span>
                            <span class="ApprovedStatusTE req_data"></span>
                            <span class="ApprovedByTE req_data"></span>
                            <span class="ApprovedDateTE req_data"></span>
                        </p>
                        <p class="signature"><span class="sig_label">Final</span>
                            <span class="FinalApprovedStatus req_data"></span>
                            <span class="FinalApprovedBy req_data"></span>
                            <span class="FinalApprovedDate req_data"></span>
                        </p>
                    </div>

                    <div class="vendor_info">
                        <p class="float_left" style="font-size:14px">Vendor Information:</p>
                        <p class="float_right" style="font-size:14px">Vendor #: <span class="ven_num req_data">34752</span></p>
                        <!-- <p class="clear_all"></p> -->
                        <span class="vendor_info req_data">AT&amp;T MOBILITY<br>PO BOX 6463<br>CAROL STREAM, IL 60197</span>
                        <p class="float_right" style="width:25px; margin-top:20px; font-size:13px">
                            <span>W9</span>
                            <br>
                            <input type="checkbox" disabled="">
                            <br>
                            <span>FD</span>
                            <br>
                            <input type="checkbox" disabled="">
                        </p>
                        <p class="clear_all"></p>
                        <p class="comm">
                            <label>Phone</label>
                            <span class="ven_phone req_data">800-331-0500</span><br>
                            <label>Fax</label>
                            <span class="ven_fax req_data"></span>
                        </p>
                    </div>

                    <div class="shipping_info">
                        <p class="black">Ship To:</p>
                        <span class="shippingInfo req_data">CINDY SMITH<br>Putnam City Operations<br>5401 NW 40th St<br>Oklahoma City, Ok 73122<br></span>
                        <p class="clear_all"></p>
                        <p class="comm">
                            <label>Phone</label>
                            <span class="ship_phone req_data">405-495-5200</span>
                            <label>Fax</label>
                            <span class="ship_fax req_data"></span>
                        </p>
                    </div>

                    <p class="clear_all"></p>
                    <span class="instructions">Instructions</span>
                    <div class="instructions">
                        <span class="instructions_print"></span>
                    </div>

                    <div class="small_details">
                        <table>
                            <tbody><tr>
                                <td>FY</td>
                                <td>Fund</td>
                                <td>Project</td>
                                <td>Function</td>
                                <!-- <td>Obj</td> -->
                                <td>Program</td>
                                <td>Subject</td>
                                <td>Ship Site</td>
                                <td>Site</td>
                                <td>Encumbrance No.</td>
                            </tr>
                            <tr>
                                <td class="fy">0</td>
                                <td class="fund">11</td>
                                <td class="project">096</td>
                                <td class="function">2660</td>
                                <td class="program">000</td>
                                <td class="subject">0000</td>
                                <td class="job">096</td>
                                <td class="site">096</td>
                                <td class="encumbrance">0-00177-096</td>
                            </tr>
                            </tbody></table>
                    </div>
                    <div class="lineitems">
                        <table>
                            <tbody><tr>
                                <td>Qty</td>
                                <td>Fixed Asset</td>
                                <td>Item No.</td>
                                <td>Item Description</td>
                                <td>Object Code</td>
                                <td>Unit price</td>
                                <td style="text-align:right">Total</td>
                            </tr>
                            <tr class="" style="font-size:14px; height:20px"><td>1</td><td> </td><td></td><td>CELL SERVICE FOR THE CHIEF OPERATIONS OFFICER</td><td>530</td><td>$1,000.00</td><td style="text-align:right">$1,000.00</td></tr><tr class="" style="font-size:14px; height:20px"><td>1</td><td> </td><td></td><td>FOR 2019-2020 SCHOOL YEAR</td><td></td><td style="text-align:right"></td><td style="text-align:right">$0.00</td></tr><tr class="" style="font-size:14px; height:20px"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr class="" style="font-size:14px; height:20px"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr class="" style="font-size:14px; height:20px"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr class="" style="font-size:14px; height:20px"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr class="" style="font-size:14px; height:20px"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr class="" style="font-size:14px; height:20px"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr class="" style="font-size:14px; height:20px"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr class="" style="font-size:14px; height:20px"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr><tr class="grand_t" style="font-size:14px; height:20px"><td class="no_border_right"></td><td class="no_border_left no_border_right"></td><td class="no_border_left no_border_right"></td><td class="no_border_left no_border_right"></td><td class="no_border_left no_border_right"></td><td class="" style="text-align:right;"><span class="total_label">Total</span></td><td style="text-align:right;"><span class="grandtotal req_data">$1,000.00</span></td></tr></tbody></table>
                        <!-- <p class="total"><span class="total_label">Total</span><span class="grandtotal req_data"></span></p> -->
                        <p class="foot" style="font-size:14px">Charge To: 096/2019-20 /  / 096</p>
                        <div class="textdisplay"></div>
                        <textarea class="req_note" name="req_note"></textarea>
                        <button class="send_comment">Save Comment</button>
                    </div>







                    <!-- </div> -->
                </div>
            </div>
            </t-modal>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'workflow-table',
        data() {
            return {
                requisitions: [],
                loading: true,
            }
        },
        mounted() {
            axios.get('/staff/workflowBackend/budgetTracker')
                .then(response => {
                    if (response.data.length > 0){
                        this.requisitions = response.data;
                    }
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.loading = false;
                });
        }
    }
</script>

<style>
    .loader,
    .loader:after {
        border-radius: 50%;
        width: 10em;
        height: 10em;
    }
    .loader {
        margin: 60px auto;
        font-size: 10px;
        position: relative;
        text-indent: -9999em;
        border-top: 1.1em solid rgba(47, 55, 70, 0.2);
        border-right: 1.1em solid rgba(47, 55, 70, 0.2);
        border-bottom: 1.1em solid rgba(47, 55, 70, 0.2);
        border-left: 1.1em solid #2F3746;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-animation: load8 1.1s infinite linear;
        animation: load8 1.1s infinite linear;
    }
    @-webkit-keyframes load8 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    @keyframes load8 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
</style>
