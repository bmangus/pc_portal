<template>
    <div class="flex w-full">
        <div class="w-full m-2 col">
            <div class="h4">Please let us know why you are rejecting this requisition:</div>
            <div class="w-full p-2 mt-4 border-2 border-black">
                <textarea class="w-full" type="text" v-model="currentComment" placeholder="Add Your Comment Here...."></textarea>
            </div>
            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 mt-2 rounded float-right" @click="saveComment"><font-awesome-icon icon="times-circle"/></button>
        </div>
    </div>
</template>

<script>
    export default {
        name:'at-workflow-rejection-modal',
        props: [
            'requisitionId', 'actor'
        ],
        data() {
            return {
                currentComment: null,
            }
        },
        methods: {
            rejectRequisition(){
                let actorString = (this.actor !== "") ? '/' + this.actor : "";
                axios.get('/staff/atWorkflowBackend/' + this.requisitionId + '/Rejected' + actorString)
                    .then(res=>{
                        this.$emit('load', true);
                    })
                    .catch(err=>this.$emit('load', true))
            },
            saveComment(){
                if(this.currentComment === null|| this.currentComment.length <= 0) {
                    alert('You must provide a comment before rejecting.');
                    return false;
                }
                const formData = new FormData();
                formData.append('comment', this.currentComment);
                formData.append('username', this.actor);
                axios.post('/staff/atWorkflowApi/addComment/' + this.requisitionId, formData)
                    .then(res => this.rejectRequisition())
                    .catch(err => console.log(error))
            }
        }
    }
</script>
