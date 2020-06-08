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
            <div v-if="this.actor" class="mt-3 ml-2 text-red-500">Currently Acting As: {{this.actor}}</div>
            <span class="btn-primary mb-2 p-3 mr-2 ml-auto" @click="manualSync"><span v-if="loadingSync"><font-awesome-icon icon="spinner" spin/></span>Sync</span>
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
                                ${{parseFloat(props.row.GrandTotal).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}}
                            </td>
                            <td :class="props.tdClass">
                                <t-input-group>
                                    <t-button class="w-full" size="sm" variant="primary" @click="openModal(props.rowIndex)">Open</t-button>
                                    <t-button class="w-full" size="sm" variant="secondary" @click="openReassignModal(props.rowIndex)">Re-Assign</t-button>
                                </t-input-group>
                            </td>
                            <t-modal :ref="'modal'+ props.rowIndex" :width="width">
                               <workflow-requisition-modal :imgurl="imgurl" :row="props.row" :rowIndex="props.rowIndex" :actor="actor" v-on:load="loadActive"/>
                            </t-modal>
                            <t-modal :ref="'reassign-modal' + props.rowIndex" :width="450">
                                <workflow-reassign-modal :row="props.row" :rowIndex="props.rowIndex" :actor="actor" v-on:load="loadActive"/>
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
        props: ['imgurl'],
        data() {
            return {
                requisitions: [],
                loading: true,
                loadingSync: false,
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
            openReassignModal(id){
                this.$refs['reassign-modal'+id].show();
            },
            loadActive(){
                this.selectedStatus = "My Requisitions";
                this.actor = "";
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
                this.actor = "";
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
                this.actor = "";
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
                        this.requisitions = response.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error);
                        this.loading = false;
                    });
            },
            manualSync(){
                this.loadingSync = true;
                axios.get('/staff/workflowBackendSync')
                    .then(res=>{
                        console.log(res);
                        this.loadingSync = false;
                    })
                    .catch(err=>{
                        console.log(err);
                        this.loadingSync = false;
                    })
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
