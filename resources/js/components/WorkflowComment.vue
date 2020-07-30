<template>
    <div class="flex w-full">
        <div class="w-full m-2 col">
            <span>Approver Comments:</span>
            <div class="w-full p-2 border-2 h-16 border-black">
                <div v-for="c in comments">{{c}}</div>
            </div>
            <div v-if="(status !== 'Approved') && (status !== 'Rejected')">
                <div class="w-full p-2 mt-4 border-2 border-black">
                    <label>
                        Your Comment:
                    </label>
                    <textarea class="w-full" type="text" v-model="currentComment" placeholder="Add Your Comment Here...."></textarea>
                </div>
                <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 mt-2 rounded float-right" @click="saveComment"><font-awesome-icon icon="save"/></button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name:'worflow-comment',
        props: [
            'requisitionId', 'actor', 'status'
        ],
        data() {
            return {
                currentComment: null,
                comments: []
            }
        },
        mounted(){
            this.getComments();
        },
        methods: {
            getComments(){
                axios.get('/staff/workflowApi/getComments/' + this.requisitionId)
                    .then( res => this.comments = res.data )
                    .catch( error => console.log(error));
            },
            saveComment(){
                const formData = new FormData();
                formData.append('comment', this.currentComment);
                formData.append('username', this.actor);
                axios.post('/staff/workflowApi/addComment/' + this.requisitionId, formData)
                    .then(res => this.getComments())
                    .catch(err => console.log(error))
            }
        }
    }
</script>
