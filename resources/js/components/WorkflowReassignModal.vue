<template>
    <div class="flex w-full">
        <div class="w-full m-2 col">
            <div class="h4">Reassign Purchase Order<br> #{{row.PONumber}}</div>
            <hr>
            <form class="w-full max-w-lg">
                <div class="text-gray-600 text-sm mb-4">Reassign To:</div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <select class="" @change="changeSelectedApprover($event)">
                        <option value="" selected disabled>--- Please Choose ---</option>
                        <option v-for="Approver in orderedApprovers" :value="Approver.Approver" :key="Approver.ApproverLName">{{Approver.ApproverLName}}, {{Approver.ApproverFName}}</option>
                    </select>
                </div>
            </form>
            <t-button class="float-right" variant="primary" size="sm" @click="submitApprover"><font-awesome-icon icon="paper-plane"/></t-button>
        </div>
    </div>
</template>

<script>
    export default {
        name:'workflow-reassign-modal',
        props: [
            'row', 'rowIndex', 'actor'
        ],
        data() {
            return {
                Approvers: null,
                selectedApprover: null,
            }
        },
        mounted() {
            this.getApprovers();
        },
        methods: {
            getApprovers(){
                axios.get('/staff/workflowApprovers')
                .then(res=>{
                    console.log(res);
                    this.Approvers = res.data;
                })
                .catch(err=>console.log(err));
            },
            changeSelectedApprover(event){
              this.selectedApprover = event.target.options[event.target.options.selectedIndex].value
            },
            submitApprover(){
                if(this.selectedApprover === null) return false;
                const formData = new FormData();
                formData.append('to_user', this.selectedApprover);
                formData.append('from_user', this.actor);
                axios.post('/staff/workflowReassign/' + this.row.pk, formData)
                    .then(res=>{
                        console.log(res);
                        this.$emit('load', true);
                    })
                    .catch(err=>console.log(err));
            }
        },
        computed: {
            orderedApprovers: function(){
                return _.orderBy(this.Approvers, 'ApproverLName');
            }
        }
    }
</script>
