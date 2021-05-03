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
                    <p class="font-extrabold">PUTNAM CITY PUBLIC SCHOOLS</p>
                    <div class="row ml-0">
                        <div class="w-1/2">
                            <p>School Activity Fund</p>
                            <p>Requisition/Purchase Order</p>
                        </div>
                        <div class="w-1/2">
                            <p><strong>Requisition Date:</strong> <span class="req_date req_data">{{row.Date}}</span></p>
                            <p><strong>Purchase No.:</strong> <span class="ponum req_data" style="text-align: right;width: 105px">{{row.PONumber}}</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex w-full">
                <div class="w-1/2 col">
                    <div style="height: 250px;" class="m-2 p-2 border-2 border-black">
                        <div class="flex w-full mb-3">
                            <p class="object-left">Vendor Information:</p>
                            <p class="m-auto">
                                <span>W9</span>
                                <input type="checkbox" disabled="">
                                <span>FD</span>
                                <input type="checkbox" disabled="">
                            </p>
                            <p class="mr-2 ml-auto">Vendor #: <span class="ven_num req_data">{{row.VendorID}}</span></p>
                        </div>
                        <div class="flex w-full">
                            <span class="vendor_info req_data">{{caseString(row.Vendor)}}<br>{{caseString(row.VendorAddress1)}}<br>{{caseString(row.VendorCity)}}, {{caseString(row.VendorState)}} {{row.VendorZip}} - {{row.VendorZipPlus}}</span>

                        </div>
                        <div class="flex w-full">

                            <p class="clear_all"></p>
                            <p class="comm">
                                <label>Phone</label>
                                <span class="ven_phone req_data">{{row.VendorPhone}}</span><br>
                                <label>Fax</label>
                                <span class="ven_fax req_data">{{row.VendorFax}}</span>
                            </p>
                        </div>
                    </div>
                    <p class="m-4">
                        <b>Requested By:</b> {{row.RequestedBy}}
                    </p>
                    <p class="m-4">
                        <b>Project - Sub Acct - Site:</b> {{row.ProjectCode_Sub}}
                    </p>
                </div>
                <div class="w-1/2 col">
                    <div style="height: 250px;" class="m-2 p-2 border-2 border-black">
                        <p class="bg-black text-white p-1 mb-3">Ship To & Bill To:</p>
                        <span class="vendor_info req_data">{{caseString(row.Attn)}}<br>{{caseString(row.ShippingCompany)}}<br>{{caseString(row.ShippingAddress)}}<br>{{caseString(row.ShippingCity)}}, {{caseString(row.ShippingState)}} {{caseString(row.ShippingZip)}} - {{caseString(row.ShippingZipPlus)}}</span>
                        <p class="clear_all"></p>
                        <p class="comm">
                            <label>Phone</label>
                            <span class="ship_phone req_data">{{row.ShipToPhone}}</span><br>
                            <label>Fax</label>
                            <span class="ship_fax req_data">{{row.ShipFax}}</span>
                        </p>
                    </div>
                    <p class="m-4">
                        <b>School Activity Fund Custodian:</b> {{row.Custodian}}
                    </p>
                    <p class="m-4">
                        <b>Account:</b> {{row.a_ProjectName}}
                    </p>
                </div>
            </div>

            <div class="flex w-full mx-2 px-3">
                <span>Note</span>
            </div>
            <div class="flex w-full">
                <div class="w-full col">
                    <div class="m-2 p-2 border-2 border-black">
                        {{(row.Note.length < 1) ? "No note available." : row.Note}}
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
                                <td class="w-2/5 border-2 border-black">Item Description</td>
                                <td class="w-1/12 border-2 border-black">Unit price</td>
                                <td class="w-1/6 border-2 border-black">Total</td>
                                <td class="w-1/12 border-2 border-black">Func</td>
                                <td class="w-1/12 border-2 border-black">Obj</td>
                                <td class="w-1/12 border-2 border-black">Prg</td>
                                <td class="w-1/12 border-2 border-black">Subj</td>

                            </tr>
                            <tr v-for="item in row.requisition_items">
                                <td class="border-2 border-black">{{item.QtyAmt}}</td>
                                <td class="border-2 border-black">{{item.Description}}</td>
                                <td class="border-2 border-black">${{parseFloat(item.unitPrice).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}}</td>
                                <td class="border-2 border-black">${{parseFloat(item.Total).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}}</td>
                                <td class="border-2 border-black">{{item.Function}}</td>
                                <td class="border-2 border-black">{{item.Object}}</td>
                                <td class="border-2 border-black">{{item.Program}}</td>
                                <td class="border-2 border-black">{{item.Subject}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <t-modal ref="modal-forward" :width="width">
                <at-workflow-forward-modal-22 :row="row" :rowIndex="rowIndex" :actor="actor" v-on:load="hideModal"/>
            </t-modal>
            <t-modal ref="rejection-modal" :width="width">
                <at-workflow-rejection-modal-22 :requisition-id="row.pk" :actor="actor" v-on:load="emitLoad"/>
            </t-modal>
        </div>
    </div>
</template>

<script>
    import jsPDF from 'jspdf';

    export default {
        name: 'at-workflow-requisition-modal-22',
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
                axios.get('/staff/22/atWorkflowBackend/' + id + '/Approved' + actorString)
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
                axios.get('/staff/22/atWorkflowBackend/' + id + '/Rejected' + actorString)
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
                window.open('/staff/22/atWorkflowPDF/download/' + id, '_blank');
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
