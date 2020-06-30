<template>
    <div class="flex w-full">
        <div class="w-full m-2 col">
            <div class="h4">Forward Purchase Order #{{row.PONumber}}</div>
            <hr>
            <form class="w-full max-w-lg">
                <div class="text-gray-600 text-sm mb-4">Recipient Information:</div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-900 text-xs font-bold mb-2" for="grid-first-name">
                            First Name
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" type="text" placeholder="John" v-model="toFirst">
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-900 text-xs font-bold mb-2" for="grid-last-name">
                            Last Name
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" type="text" placeholder="Doe" v-model="toLast">
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label class="block uppercase tracking-wide text-gray-900 text-xs font-bold mb-2" for="grid-email">
                            Email
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-email" type="email" placeholder="someone@putnamcityschools.org" v-model="toEmail">
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full px-3">
                        <label class="block uppercase tracking-wide text-gray-900 text-xs font-bold mb-2" for="grid-message">
                            Message
                        </label>
                        <textarea class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-message" type="email" placeholder="Please supply a custom message so that the recipient understands why they are receiving this pdf." v-model="message"></textarea>
                    </div>
                </div>
            </form>
            <t-button class="float-right" variant="primary" size="sm" @click="submitForward"><font-awesome-icon icon="paper-plane"/></t-button>
        </div>
    </div>
</template>

<script>
    export default {
        name:'at-workflow-forward-modal',
        props: [
            'row', 'rowIndex', 'actor'
        ],
        data() {
            return {
                toFirst: null,
                toLast: null,
                toEmail: null,
                message: null
            }
        },
        methods: {
            submitForward(){
                const formData = new FormData();
                formData.append('id', this.row.pk);
                formData.append('recipientEmail', this.toEmail);
                formData.append('custMessage', this.message);
                axios.post('/staff/atWorkflowPDF/forward', formData)
                    .then(res=>{
                        console.log(res);
                        this.$emit('load', true);
                    })
                    .catch(err=>{
                        console.log(err)
                        alert('Something went wront. Please contact IT for assistance.');
                    });
            }
        }
    }
</script>
