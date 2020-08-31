<template>
    <div class="w-1/6 h-full bg-gray-800 hidden lg:flex">
        <ul class="flex-col w-full pt-2">
            <li class="bg-gray-800 w-full mx-auto py-2 px-auto text-center justify-center hover:bg-gray-700 focus:bg-gray-900 text-white">Dashboard</li>
            <li class="flex flex-row bg-gray-800 w-full mx-auto py-2 px-auto text-center justify-center hover:bg-gray-700 focus:bg-gray-900 text-white">
                <a href="/staff/workflow">Budget Tracker</a>
                <span class="flex rounded-full bg-red-700 px-2 py-1 text-white text-xs font-bold ml-3">{{ this.btCount }}</span>
            </li>
            <li class="flex flex-row bg-gray-800 w-full mx-auto py-2 px-auto text-center justify-center hover:bg-gray-700 focus:bg-gray-900 text-white">
                <a href="/staff/ATworkflow">Activity Tracker</a>
                <span class="flex rounded-full bg-red-700 px-2 py-1 text-white text-xs font-bold ml-3">{{ this.atCount }}</span>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    name: 'navbar-desktop',
    props: ['user'],
    data() {
        return {
            open: false,
            userJson: JSON.parse(this.user),
            loading: false,
            btCount: 0,
            atCount: 0
        }
    },
    mounted(){
        console.log(this.asset);
        console.log(this.user);
        axios.get('/staff/workflowBackendCounts')
            .then((res)=>{
                this.btCount = res.data.btCount;
                this.atCount = res.data.atCount;
            })
            .catch((err)=>console.log(err));
    },
    methods: {
        toggle() {
            this.open = !this.open
        },
        logout() {
            this.loading = true;
            axios.post('/logout')
                .then(response=>{
                    console.log('testing logout...........');
                    window.location.href='/login';
                })
                .catch(err=>{
                    console.log(err);
                    window.location.href='/login';
                })
        }
    }
}
</script>
