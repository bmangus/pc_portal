<template>
    <div class="w-full m-3">
        <div class="flex">
            <t-dropdown :text="selectedStatus" class="pb-2">
                <ul>
                    <li>
                        <a class="block no-underline px-4 py-2 hover" @click="loadActive">My Requisitions</a>
                    </li>
                    <li>
                        <a class="block no-underline px-4 py-2 hover" @click="loadCompleted">Completed</a>
                    </li>
                    <li>
                        <a class="block no-underline px-4 py-2 hover" @click="loadRejected">Rejected</a>
                    </li>
                    <li>
                        <a class="block no-underline px-4 py-2 hover" @click="$refs.acting.show()">Act As</a>
                    </li>
                </ul>
            </t-dropdown>
            <span class="btn-primary mb-2 p-3 mr-2 ml-auto" @click="manualSync">Sync</span>
        </div>
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
                                <t-button size="sm" variant="primary" @click="openModal(props.rowIndex)">Open</t-button>
                                <t-button size="sm" variant="secondary" >Forward</t-button>
                            </td>
                            <t-modal :ref="'modal'+ props.rowIndex" :width="width">
                                <div class="requisitions">
                                    <div class="header">
                                        <img src="https://staff.putnamcityschools.org/.pc/site/assets/img/pc_main_logo.png" class="print_logo">
                                        <p class="float_left" style="font-weight:bold;">PUTNAM CITY PUBLIC SCHOOLS ACCOUNTS PAYABLE</p>
                                        <div class="col_left float_left">
                                            <p>5401 NW 40th  - Oklahoma City, OK 73122</p>
                                            <p>Phone:(405) 495-5200  Fax (405) 495-8648</p>
                                        </div>
                                        <div class="col_right float_right">
                                            <p><strong>Requisition Date:</strong> <span class="req_date req_data">{{props.row.Date}}</span></p>
                                            <p><strong>Purchase No.:</strong> <span class="ponum req_data" style="text-align: right;width: 105px">{{props.row.PONumber}}</span></p>
                                            <p><span class="created_by req_data float_right">&nbsp;operations</span><strong>Created By:</strong></p>
                                        </div>
                                        <p class="header_final">FINAL APPROVAL MUST BE OBTAINED PRIOR TO PURCHASE</p>
                                        <p class="clear_all"></p>
                                    </div>
                                    <div class="mid_header">

                                        <div class="billing">
                                            <span class="bill_to_label">Bill To:</span>
                                            <span class="BillToInfo req_data">{{props.row.BillToAttention}}<br>{{props.row.BillToName}}<br>{{props.row.BillToAddress}}<br>{{props.row.BillToCity}}, {{props.row.BillToState}} {{props.row.BillToZip}}<br></span>
                                            <p class="clear_all"></p>
                                            <br><br><br>
                                            <p class="signature"><span class="sig_label">1st</span>
                                                <span class="ApprovedStatus1 req_data">{{props.row.ApprovedStatus1}}</span>
                                                <span class="ApprovedBy1 req_data">{{props.row.ApprovedBy1}}</span>
                                                <span class="ApprovedDate1 req_data">{{props.row.ApprovedDate1}}</span>
                                            </p>
                                            <p class="signature"><span class="sig_label">2nd</span>
                                                <span class="ApprovedStatus1 req_data">{{props.row.ApprovedStatus2}}</span>
                                                <span class="ApprovedBy1 req_data">{{props.row.ApprovedBy2}}</span>
                                                <span class="ApprovedDate1 req_data">{{props.row.ApprovedDate2}}</span>
                                            </p>
                                            <p class="signature"><span class="sig_label">3rd</span>
                                                <span class="ApprovedStatus1 req_data">{{props.row.ApprovedStatus3}}</span>
                                                <span class="ApprovedBy1 req_data">{{props.row.ApprovedBy3}}</span>
                                                <span class="ApprovedDate1 req_data">{{props.row.ApprovedDate3}}</span>
                                            </p>
                                            <p class="signature"><span class="sig_label">4th</span>
                                                <span class="ApprovedStatus1 req_data">{{props.row.ApprovedStatus4}}</span>
                                                <span class="ApprovedBy1 req_data">{{props.row.ApprovedBy4}}</span>
                                                <span class="ApprovedDate1 req_data">{{props.row.ApprovedDate4}}</span>
                                            </p>
                                            <p class="signature"><span class="sig_label">5th</span>
                                                <span class="ApprovedStatus1 req_data">{{props.row.ApprovedStatus5}}</span>
                                                <span class="ApprovedBy1 req_data">{{props.row.ApprovedBy5}}</span>
                                                <span class="ApprovedDate1 req_data">{{props.row.ApprovedDate5}}</span>
                                            </p>
                                            <p class="signature"><span class="sig_label">TE</span>
                                                <span class="ApprovedStatusTE req_data">{{props.row.ApprovedStatusTE}}</span>
                                                <span class="ApprovedByTE req_data">{{props.row.ApprovedByTE}}</span>
                                                <span class="ApprovedDateTE req_data">{{props.row.ApprovedDateTE}}</span>
                                            </p>
                                            <p class="signature"><span class="sig_label">Final</span>
                                                <span class="FinalApprovedStatus req_data">{{props.row.FinalApprovedStatus}}</span>
                                                <span class="FinalApprovedBy req_data">{{props.row.FinalApprovedBy}}</span>
                                                <span class="FinalApprovedDate req_data">{{props.row.FinalApprovedDate}}</span>
                                            </p>
                                        </div>

                                        <div class="vendor_info">
                                            <p class="float_left" style="font-size:14px">Vendor Information:</p>
                                            <p class="float_right" style="font-size:14px">Vendor #: <span class="ven_num req_data">{{props.row.VendorID}}</span></p>
                                            <!-- <p class="clear_all"></p> -->
                                            <span class="vendor_info req_data">{{props.row.Vendor}}<br>{{props.row.VendorAddress}}<br>{{props.row.VendorCity}}, {{props.row.VendorState}} {{props.row.VendorZip}}</span>
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
                                                <span class="ven_phone req_data">{{props.row.VendorPhone}}</span><br>
                                                <label>Fax</label>
                                                <span class="ven_fax req_data"></span>
                                            </p>
                                        </div>

                                        <div class="shipping_info">
                                            <p class="black">Ship To:</p>
                                            <span class="shippingInfo req_data">{{props.row.ShippingInfo}}</span>
                                            <p class="clear_all"></p>
                                            <p class="comm">
                                                <label>Phone</label>
                                                <span class="ship_phone req_data">{{props.row.ShipToPhone}}</span>
                                                <label>Fax</label>
                                                <span class="ship_fax req_data">{{props.row.ShipFax}}</span>
                                            </p>
                                        </div>

                                        <p class="clear_all"></p>
                                        <span class="instructions">Instructions</span>
                                        <div class="instructions">
                                            <span class="instructions_print">{{props.row.Instructions}}</span>
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
                                                    <td class="fy">{{props.row.FiscalYear}}</td>
                                                    <td class="fund">{{props.row.Fund}}</td>
                                                    <td class="project">{{props.row.Project}}</td>
                                                    <td class="function">{{props.row.Function}}</td>
                                                    <td class="program">{{props.row.Program}}</td>
                                                    <td class="subject">{{props.row.Subject}}</td>
                                                    <td class="job">{{props.row.JobClass}}</td>
                                                    <td class="site">{{props.row.Site}}</td>
                                                    <td class="encumbrance">{{props.row.PONumber}}</td>
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
                                                <tr v-for="item in props.row.requisition_items" class="" style="font-size:14px; height:20px">
                                                    <td>{{item.Qty}}</td>
                                                    <td>{{item.FixedAsset}}</td>
                                                    <td>{{item.ItemCount}}</td>
                                                    <td>{{item.Description}}</td>
                                                    <td>{{item.Object}}</td>
                                                    <td>{{parseFloat(item.UnitPrice).toFixed(2)}}</td>
                                                    <td style="text-align:right">{{parseFloat(item.Qty * item.UnitPrice).toFixed(2)}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <p class="foot" style="font-size:14px">Charge To: {{props.row.ChargeTo}}</p>
                                            <p>Comments Go Here.....</p>
                                            <div class="flex">
                                                <span class="btn-success p-3 ml-auto mr-2" @click="approveReq(props.row.pk, props.rowIndex)">Approve</span>
                                                <span class="btn-danger p-3 mr-2" @click="rejectReq(props.row.pk, props.rowIndex)">Reject</span>
                                            </div>
                                        </div>







                                        <!-- </div> -->
                                    </div>
                                </div>
                            </t-modal>
                        </tr>
                    </template>
                </t-table>
            </div>
        <t-modal ref="acting" class="align-middle object-center">
            <div class="flex mb-4"><p class="mx-auto text-2xl text-center">Impersonation:</p></div>
            <div class="flex mb-4"><span class="w-1/3 text-right">Username:</span><div class="w-2/3"><t-input v-model="actor" name="my-input" class="ml-2"/></div></div>
            <div class="flex mb-4"><button class="btn-primary mt-2 mx-auto p-2" @click="impersonate">Act As This User</button></div>
        </t-modal>
    </div>
</template>

<script>
    export default {
        name: 'workflow-table',
        data() {
            return {
                requisitions: [],
                loading: true,
                modalWidth: "768",
                selectedStatus: "My Requisitions",
                actor: "",
            }
        },
        mounted() {
            this.loadActive();
        },
        methods: {
            openModal(id){
                this.$refs['modal'+id].show();
            },
            loadActive(){
                this.selectedStatus = "My Requisitions";
                axios.get('/staff/workflowBackend/budgetTracker')
                    .then(response => {
                        this.requisitions = response.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error);
                        this.loading = false;
                    });
            },
            loadCompleted(){
                this.selectedStatus = "Completed";

                axios.get('/staff/workflowBackend/budgetTracker/Completed')
                    .then(response => {
                        this.requisitions = response.data;

                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error);
                        this.loading = false;
                    });
            },
            loadRejected(){
                this.selectedStatus = "Rejected";

                axios.get('/staff/workflowBackend/budgetTracker/Rejected')
                    .then(response => {
                        this.requisitions = response.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error);
                        this.loading = false;
                    });
            },
            impersonate(){
                this.$refs.acting.hide();
                axios.get('/staff/workflowBackend/budgetTracker/user/' + this.actor)
                    .then(response => {
                        if (response.data.length){
                            this.requisitions = response.data;
                        }
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error);
                        this.loading = false;
                    });
            },
            manualSync(){
                axios.get('/staff/workflowBackend/budgetTracker/sync')
                    .then(res=>console.log(res))
                    .catch(err=>console.log(err))
            },
            approveReq(id){
                let actorString = (this.actor !== "") ? '/' + actor : "";
                axios.get('/staff/workflowBackend/budgetTracker/' + id + '/Approved' + actorString)
                    .then(res=>{
                        this.$refs['modal'+mid].hide();
                        this.loadActive()
                    })
                    .catch(err=>this.loadActive())
            },
            rejectReq(id, mid){
                let actorString = (this.actor !== "") ? '/' + actor : "";
                axios.get('/staff/workflowBackend/budgetTracker/' + id + '/Rejected' + actorString)
                    .then(res=>{
                        this.$refs['modal'+mid].hide();
                        this.loadActive()
                    })
                    .catch(err=>this.loadActive())
            }
        },
        computed: {
            width(){
                return window.innerWidth * .85;
            }
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
