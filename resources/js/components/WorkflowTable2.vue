<template>
    <div class="w-full m-3">
        <div class="text-xl font-bold mb-2">Budget Tracker</div>
        <hr/>
        <div class="flex">
            <t-dropdown :text="selectedStatus" class="pb-2">
                <ul>
                    <li>
                        <a class="block no-underline px-4 py-2 hover" @click="loadActive">My Requisitions</a>
                    </li>
                    <li>
                        <a class="block no-underline px-4 py-2 hover" @click="loadCompleted">Approved</a>
                    </li>
                    <li>
                        <a class="block no-underline px-4 py-2 hover" @click="loadRejected">Rejected</a>
                    </li>
                    <li v-if="authUser.SuperUser === 'Yes'">
                        <a class="block no-underline px-4 py-2 hover" @click="$refs.acting.show()">Act As</a>
                    </li>
                </ul>
            </t-dropdown>
            <div v-if="this.actor.length > 0" class="mt-3 mx-auto">
                <span class="text-dark">Acting As: {{this.actor}}</span><span class="btn-outline-secondary mb-2 p-3 mr-2 ml-2" @click="()=>this.actor = ''">Clear</span>
            </div>
            <div class="mt-3 ml-auto mr-2">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" v-model="searchStr" placeholder="Search.."/>
            </div>
            <span class="btn-primary mb-2 p-3 mr-2 ml-2" @click="manualSync"><span v-if="loadingSync"><font-awesome-icon icon="spinner" spin/></span>Sync</span>
        </div>
        <div v-if="loading" class="flex inset-auto">
            <div class="loader">Loading...</div>
        </div>
        <div v-else-if="noRequisitions" class="flex inset-auto">
            <div class="m-auto">
                <div class="font-bold text-xl mb-2">You're All Caught Up!</div>
                <p class="text-gray-700 text-base">
                    There are no more requisitions for you to approve at this time.
                </p>
            </div>
        </div>
        <div v-else-if="nullSearch" class="flex inset-auto">
            <div class="m-auto">
                <div class="font-bold text-xl mb-2">No Results:</div>
                <p class="text-gray-700 text-base">
                    No requisitions match the current filter criteria.
                </p>
            </div>
        </div>
        <div v-else>
            <table class="table-auto w-100 mr-2">
                <thead>
                    <tr class="bg-white">
                        <th v-on:click="sort('PONumber')" class="px-4 py-2">
                            <div class="flex-row">
                                PO Number
                                <font-awesome-icon class="ml-2 mt-1" v-if="this.currentSort === 'PONumber' && this.currentSortDir === 'asc'" icon="sort-up"/>
                                <font-awesome-icon class="ml-2 mt-1" v-else-if="this.currentSort === 'PONumber' && this.currentSortDir === 'desc'" icon="sort-down"/>
                                <font-awesome-icon class="ml-2 mt-1" v-else icon="sort"/>
                            </div>
                        </th>
                        <th v-on:click="sort('Vendor')" class="px-4 py-2">
                            <div class="flex-row">
                                Vendor
                                <font-awesome-icon class="ml-2 mt-1" v-if="this.currentSort === 'Vendor' && this.currentSortDir === 'asc'" icon="sort-up"/>
                                <font-awesome-icon class="ml-2 mt-1" v-else-if="this.currentSort === 'Vendor' && this.currentSortDir === 'desc'" icon="sort-down"/>
                                <font-awesome-icon class="ml-2 mt-1" v-else icon="sort"/>
                            </div>
                        </th>
                        <th v-on:click="sort('ShippingCompany')" class="px-4 py-2">
                            <div class="flex-row">
                                Site
                                <font-awesome-icon class="ml-2 mt-1" v-if="this.currentSort === 'ShippingCompany' && this.currentSortDir === 'asc'" icon="sort-up"/>
                                <font-awesome-icon class="ml-2 mt-1" v-else-if="this.currentSort === 'ShippingCompany' && this.currentSortDir === 'desc'" icon="sort-down"/>
                                <font-awesome-icon class="ml-2 mt-1" v-else icon="sort"/>
                            </div>
                        </th>
                        <th v-on:click="sort('Project')" class="px-4 py-2">
                            <div class="flex-row">
                                Project
                                <font-awesome-icon class="ml-2 mt-1" v-if="this.currentSort === 'Project' && this.currentSortDir === 'asc'" icon="sort-up"/>
                                <font-awesome-icon class="ml-2 mt-1" v-else-if="this.currentSort === 'Project' && this.currentSortDir === 'desc'" icon="sort-down"/>
                                <font-awesome-icon class="ml-2 mt-1" v-else icon="sort"/>
                            </div>
                        </th>
                        <th v-on:click="sort('GrandTotal')" class="px-4 py-2">
                            <div class="flex-row">
                                Cost
                                <font-awesome-icon class="ml-2 mt-1" v-if="this.currentSort === 'GrandTotal' && this.currentSortDir === 'asc'" icon="sort-up"/>
                                <font-awesome-icon class="ml-2 mt-1" v-else-if="this.currentSort === 'GrandTotal' && this.currentSortDir === 'desc'" icon="sort-down"/>
                                <font-awesome-icon class="ml-2 mt-1" v-else icon="sort"/>
                            </div>
                        </th>
                        <th class="px-4 py-2">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-gray-100" v-for="row in sortedRequisitions">
                        <td class="border px-4 py-2">
                            {{row.PONumber}}
                        </td>
                        <td class="border px-4 py-2">
                            {{row.Vendor}}
                        </td>
                        <td class="border px-4 py-2">
                            {{row.ShippingCompany}}
                        </td>
                        <td class="border px-4 py-2">
                            {{row.Project}}
                        </td>
                        <td class="border px-4 py-2">
                            ${{parseFloat(row.GrandTotal).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}}
                        </td>
                        <td class="border px-4 py-2">
                            <t-input-group>
                                <t-button class="w-full" size="sm" variant="primary" @click="openModal(row)">Open</t-button>
                                <t-button class="w-full" size="sm" variant="secondary" @click="openReassignModal(row)">Re-Assign</t-button>
                            </t-input-group>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <t-modal ref="acting" class="align-middle object-center">
            <div class="flex mb-4"><p class="mx-auto text-2xl text-center">Impersonation:</p></div>
            <div class="flex mb-4"><span class="w-1/3 text-right">Username:</span><div class="w-2/3"><t-input v-model="actor" name="my-input" class="ml-2"/></div></div>
            <div class="flex mb-4"><button class="btn-primary mt-2 mx-auto p-2" @click="impersonate">Act As This User</button></div>
        </t-modal>
        <t-modal ref="requisition-modal" :width="width">
            <workflow-requisition-modal :imgurl="imgurl" :row="activeRow" :actor="actor" v-on:load="load"/>
        </t-modal>
        <t-modal ref="reassign-modal" :width="450">
            <workflow-reassign-modal :row="activeRow" :rowIndex="activeRow.id" :actor="actor" v-on:load="load"/>
        </t-modal>
    </div>
</template>

<script>
    export default {
        name: 'workflow-table-2',
        props: ['imgurl', 'authUser'],
        data() {
            return {
                requisitions: [],
                loading: true,
                noRequisitions: false,
                loadingSync: false,
                modalWidth: "768",
                selectedStatus: "My Requisitions",
                actor: "",
                searchStr: "",
                currentSort: '',
                currentSortDir: 'asc',
                activeRow: []
            }
        },
        mounted() {
            this.loadActive();
        },
        methods: {
            openModal(row){
                this.activeRow = row;
                this.$refs['requisition-modal'].show();
            },
            openReassignModal(row){
                this.activeRow = row;
                this.$refs['reassign-modal'].show();
            },
            load(){
                if(this.actor === ""){
                    this.loadActive();
                }  else {
                    this.impersonate();
                }
            },
            sort(s){
                console.log('test');
              if(s === this.currentSort) {
                  this.currentSortDir = this.currentSortDir === 'asc'?'desc':'asc';
              }
              this.currentSort = s;
            },
            loadActive(){
                this.selectedStatus = "My Requisitions";
                this.noRequisitions = false;
                this.loading = true;
                this.actor = "";
                axios.get('/staff/workflowBackend/budgetTracker')
                    .then(response => {
                        if(response.data.length < 1){
                            this.noRequisitions = true;
                        } else {
                            this.noRequisitions = false;
                        }
                        this.requisitions = response.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error);
                        this.loading = false;
                    });
            },
            loadCompleted(){
                this.selectedStatus = "Approved";
                this.loading = true
                this.noRequisitions = false;
                //this.actor = "";
                axios.get('/staff/btWorkflowBackend/Approved' + ((this.actor.length > 0) ? "/" + this.actor : ""))
                    .then(response => {
                        if(response.data.length < 1){
                            this.noRequisitions = true;
                        } else {
                            this.noRequisitions = false;
                        }
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
                this.loading = true;
                this.noRequisitions = false;
                //this.actor = "";
                axios.get('/staff/btWorkflowBackend/Rejected' + ((this.actor.length > 0) ? "/" + this.actor : ""))
                    .then(response => {
                        if(response.data.length < 1){
                            this.noRequisitions = true;
                        } else {
                            this.noRequisitions = false;
                        }
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
                //this.selectedStatus = this.actor;
                this.loading = true;
                this.noRequisitions = false;
                axios.get('/staff/workflowBackend/budgetTracker/user/' + this.actor)
                    .then(response => {
                        if(response.data.length < 1){
                            this.noRequisitions = true;
                        } else {
                            this.noRequisitions = false;
                        }
                        this.requisitions = response.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error);
                        this.loading = false;
                    });
            },
            manualSync(){
                if(!this.loadingSync){
                    this.loadingSync = true;
                    axios.get('/staff/workflowBackendSync')
                        .then(res=>{
                            console.log(res);
                            if(this.actor === ""){
                                this.loadActive();
                            } else {
                                this.impersonate();
                            }

                            this.loadingSync = false;
                        })
                        .catch(err=>{
                            console.log(err);
                            alert('Something with wrong while attempting to communicate with FileMaker. Please contact IT.')
                            if(this.actor === ""){
                                this.loadActive();
                            } else {
                                this.impersonate();
                            }
                            this.loadingSync = false;
                        })
                }
            }
        },
        computed: {
            width(){
                return window.innerWidth * .85;
            },
            nullSearch(){
              return this.filteredRequisitions.length === 0;
            },
            filteredRequisitions(){
                if(this.requisitions.length > 0){
                   return this.requisitions.filter(req => {
                        return req.PONumber.toLowerCase().includes(this.searchStr.toLowerCase()) ||
                        req.Vendor.toLowerCase().includes(this.searchStr.toLowerCase()) ||
                        req.ShippingCompany.toLowerCase().includes(this.searchStr.toLowerCase()) ||
                        req.Project.toString().toLowerCase().includes(this.searchStr.toLowerCase());
                    });
                } else {
                    return [];
                }

            },
            sortedRequisitions(){
                if(this.filteredRequisitions.length > 0) {
                 return this.filteredRequisitions.sort((a,b) => {
                     var modifier = 1;
                     if(this.currentSortDir === 'desc') modifier = -1;
                     if(a[this.currentSort] <  b[this.currentSort]) return -1 * modifier;
                     if(a[this.currentSort] > b[this.currentSort]) return modifier;
                     return 0;
                    })
                } else {
                    return [];
                }
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
