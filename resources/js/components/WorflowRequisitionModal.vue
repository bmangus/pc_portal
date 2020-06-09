<template>
    <div>
        <div class="flex mb-2 mr-2 float-right">
            <button class="bg-gray-500 hover:bg-gray-700 text-white text-xl font-bold py-2 px-4 rounded ml-2" @click="print(row.pk)"><font-awesome-icon icon="file-pdf"/></button>
            <button class="bg-gray-500 hover:bg-gray-700 text-white text-xl font-bold py-2 px-4 rounded ml-2" @click="openModal" ><font-awesome-icon icon="share"/></button>
            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2" @click="approveReq(row.pk, rowIndex)">Approve</button>
            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2" @click="openRejectionModal">Reject</button>
        </div>
        <div ref='printarea' class="font-sans">
            <div class="flex w-full mb-3">
                <div class="w-1/6 border-black">
                    <img :src="imgurl" class="mx-auto object-scale-down h-24">
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
                            <p><strong>Requisition Date:</strong> <span class="req_date req_data">{{row.Date}}</span></p>
                            <p><strong>Purchase No.:</strong> <span class="ponum req_data" style="text-align: right;width: 105px">{{row.PONumber}}</span></p>
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
                            <p class="mr-2 ml-auto">Vendor #: <span class="ven_num req_data">{{row.VendorID}}</span></p>
                        </div>
                        <div class="flex w-full">
                            <span class="vendor_info req_data">{{caseString(row.Vendor)}}<br>{{caseString(row.VendorAddress)}}<br>{{caseString(row.VendorCity)}}, {{caseString(row.VendorState)}} {{row.VendorZip}}</span>
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
                                <span class="ven_phone req_data">{{row.VendorPhone}}</span><br>
                                <label>Fax</label>
                                <span class="ven_fax req_data"></span>
                            </p>
                        </div>
                    </div>

                    <div class=" m-2 p-2 border-2 border-black">
                        <p class="bg-black text-white p-1 mb-3">Ship To:</p>
                        <span class="vendor_info req_data">{{caseString(row.Attn)}}<br>{{caseString(row.ShippingCompany)}}<br>{{caseString(row.ShippingAddress)}}<br>{{caseString(row.ShippingCity)}}, {{caseString(row.ShippingState)}} {{caseString(row.ShippingZip)}}</span>
                        <p class="clear_all"></p>
                        <p class="comm">
                            <label>Phone</label>
                            <span class="ship_phone req_data">{{row.ShipToPhone}}</span><br>
                            <label>Fax</label>
                            <span class="ship_fax req_data">{{row.ShipFax}}</span>
                        </p>
                    </div>
                </div>
                <div class="w-3/5 m-2 p-2 border-2 border-black">
                    <div class="flex w-full mb-4">
                        <div class="w-1/6 text-center">
                            <span class="mx-auto">Bill To:</span>
                        </div>
                        <div class="w-5/6">
                            <span class="text-left">{{caseString(row.BillToAttention)}}<br>{{caseString(row.BillToName)}}<br>{{caseString(row.BillToAddress)}}<br>{{caseString(row.BillToCity)}}, {{caseString(row.BillToState)}} {{row.BillToZip}}<br></span>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">1st</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{row.ApprovedStatus1}}</div>
                                <div class="w-1/3">{{row.ApprovedBy1}}</div>
                                <div class="w-1/3">{{row.ApprovedDate1}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">2nd</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{row.ApprovedStatus2}}</div>
                                <div class="w-1/3">{{row.ApprovedBy2}}</div>
                                <div class="w-1/3">{{row.ApprovedDate2}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">3rd</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{row.ApprovedStatus3}}</div>
                                <div class="w-1/3">{{row.ApprovedBy3}}</div>
                                <div class="w-1/3">{{row.ApprovedDate3}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">4th</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{row.ApprovedStatus4}}</div>
                                <div class="w-1/3">{{row.ApprovedBy4}}</div>
                                <div class="w-1/3">{{row.ApprovedDate4}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">5th</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{row.ApprovedStatus5}}</div>
                                <div class="w-1/3">{{row.ApprovedBy5}}</div>
                                <div class="w-1/3">{{row.ApprovedDate5}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">TE</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{row.ApprovedStatusTE}}</div>
                                <div class="w-1/3">{{row.ApprovedByTE}}</div>
                                <div class="w-1/3">{{row.ApprovedDateTE}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full mb-2">
                        <div class="w-1/6 text-center">Final</div>
                        <div class="w-5/6 border-b">
                            <div class="flex w-full">
                                <div class="w-1/3">{{row.FinalApprovedStatus}}</div>
                                <div class="w-1/3">{{row.FinalApprovedBy}}</div>
                                <div class="w-1/3">{{row.FinalApprovedDate}}</div>
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
                        {{(row.Instructions.length < 1) ? "No instructions available." : row.Instructions}}
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
                                <td class="border-2 border-black">{{row.FiscalYear}}</td>
                                <td class="border-2 border-black">{{row.Fund}}</td>
                                <td class="border-2 border-black">{{row.Project}}</td>
                                <td class="border-2 border-black">{{row.Function}}</td>
                                <td class="border-2 border-black">{{row.Program}}</td>
                                <td class="border-2 border-black">{{row.Subject}}</td>
                                <td class="border-2 border-black">{{row.JobClass}}</td>
                                <td class="border-2 border-black">{{row.Site}}</td>
                                <td class="border-2 border-black">{{row.PONumber}}</td>
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
                                <td class="w-1/12 border-2 border-black">Item No.</td>
                                <td class="w-2/5 border-2 border-black">Item Description</td>
                                <td class="w-1/12 border-2 border-black">Object Code</td>
                                <td class="w-1/12 border-2 border-black">Unit price</td>
                                <td class="w-1/6 border-2 border-black">Total</td>
                            </tr>
                            <tr v-for="item in row.requisition_items">
                                <td class="border-2 border-black">{{item.Qty}}</td>
                                <td class="border-2 border-black">{{item.FixedAsset}}</td>
                                <td class="border-2 border-black">{{item.ItemCount}}</td>
                                <td class="border-2 border-black">{{item.Description}}</td>
                                <td class="border-2 border-black">{{item.Object}}</td>
                                <td class="border-2 border-black">{{parseFloat(item.UnitPrice).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}}</td>
                                <td class="border-2 border-black">{{parseFloat(item.Qty * item.UnitPrice).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <p class="foot" style="font-size:14px">Charge To: {{row.ChargeTo}}</p>
            <workflow-comment :requisitionId="row.pk" :actor="actor"/>
            <t-modal ref="modal-forward" :width="width">
                <workflow-forward-modal :row="row" :rowIndex="rowIndex" :actor="actor" v-on:load="hideModal"/>
            </t-modal>
            <t-modal ref="rejection-modal" :width="width">
                <workflow-rejection-modal :requisition-id="row.pk" :actor="actor" v-on:load="emitLoad"/>
            </t-modal>
        </div>
    </div>
</template>

<script>
    import jsPDF from 'jspdf';

    export default {
        name: 'workflow-requisition-modal',
        props: ['imgurl', 'row', 'rowIndex', 'actor'],
        data() {
            return {
                doc: '',
                output: null
            }
        },
        methods: {
            approveReq(id){
                let actorString = (this.actor !== "") ? '/' + this.actor : "";
                axios.get('/staff/workflowBackend/budgetTracker/' + id + '/Approved' + actorString)
                    .then(res=>{
                        this.$refs['modal'+mid].hide();
                        this.$emit('load', true);
                    })
                    .catch(err=>this.$emit('load', true))
            },
            openRejectionModal(){
              this.$refs['rejection-modal'].show();
            },
            emitLoad(){
                this.$emit('load', true);
            },
            rejectReq(id, mid){
                let actorString = (this.actor !== "") ? '/' + this.actor : "";
                axios.get('/staff/workflowBackend/budgetTracker/' + id + '/Rejected' + actorString)
                    .then(res=>{
                        this.$refs['modal'+mid].hide();
                        this.$emit('load', true);
                    })
                    .catch(err=>this.$emit('load', true))
            },
            caseString(str) {
                if (typeof str !== "string") return str;
                const array1 = str.toLowerCase().split(' ');
                const newarray1 = [];

                for (let x = 0; x < array1.length; x++) {
                    if (array1[x].length < 3) {
                        newarray1.push(array1[x].toUpperCase());
                    } else {
                        newarray1.push(array1[x].charAt(0).toUpperCase() + array1[x].slice(1));
                    }
                }
                return newarray1.join(' ');
            },
            getDataURL() {
                return new Promise((resolve) => {
                    const el = this.$refs.printarea;
                    const options = {
                        type: 'dataURL'
                    }
                    this.doc = this.$html2canvas(el, options)
                    return resolve(this.doc);
                });
            },
            print(id){
                window.open('/staff/workflowPDF/download/' + id, '_blank');
            },
            executePrint(){
                this.printHtml2Canvas().then((output)=>{
                    const formData = new FormData();
                    formData.append('dataURL', output);
                    axios.post('/staff/workflowPDF/send/budgetTracker', formData)
                    .then(res=>console.log(res))
                    .catch(err=>console.log(err))
                })
            },
            async printHtml2Canvas() {
                const el = this.$refs.printarea;
                const options = {
                    type: 'dataURL'
                }
                this.output = await this.$html2canvas(el, options);
            },
            openModal(){
                this.$refs['modal-forward'].show();
            },
            hideModal(){
                this.$refs['modal-forward'].hide();
            }
        }
    }
</script>
<style>

</style>
