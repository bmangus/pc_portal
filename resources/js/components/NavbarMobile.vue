<template>
        <div v-else>
            <nav class="flex items-center justify-between flex-wrap bg-gray-100 p-6">
                <div class="block lg:hidden">
                    <t-dropdown class="text-sm lg:flex-grow">
                        <template v-slot:button-content>
                            <svg class="fill-current text-gray-800 h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                        </template>
                        <a href="#responsive-header" class="ml-2 flex flex-row block mt-4 lg:inline-block lg:mt-0 text-gray-800 hover:text-gray-500 mr-4 lg:hidden">
                            Dashboard
                        </a>
                        <a href="/staff/workflow" class="ml-2 flex flex-row mt-4 lg:inline-block lg:mt-0 text-gray-800 hover:text-gray-500 mr-4 lg:hidden">
                            Budget Tracker <span class="flex rounded-full bg-red-500 px-2 py-1 text-white text-xs font-bold ml-3">{{ this.btCount }}</span>
                        </a>
                        <a href="/staff/ATworkflow" class="ml-2 flex flex-row mt-4 lg:inline-block lg:mt-0 text-gray-800 hover:text-gray-500 lg:hidden">
                            Activity Tracker <span class="flex rounded-full bg-red-500 px-2 py-1 text-white text-xs font-bold ml-3">{{ this.atCount }}</span>
                        </a>
                        <a href="/staff/ATworkflow" class="ml-2 flex flex-row mt-4 lg:inline-block lg:mt-0 text-gray-800 hover:text-gray-500 lg:hidden">
                            Staff Rehires
                        </a>
                    </t-dropdown>
                </div>
                <div class="flex items-center flex-shrink-0 text-white mr-6">
                    <img class="object-center h-12" :src="asset"/>
                </div>
                    <div v-if="loading">
                        <t-dropdown class="text-blue-500" :text="'Logging Out ...'">

                        </t-dropdown>
                    </div>
                    <div v-else>
                        <t-dropdown :text="userJson.name">

                            <ul>
                                <li>
                                    <a href="#" class="block no-underline px-4 py-2 hover:bg-blue-500 hover:text-white" @click="logout">Logout</a>
                                </li>
                            </ul>
                        </t-dropdown>
                    </div>
                    <!--<a href="#" class="inline-block text-sm px-4 py-2 leading-none border rounded text-gray-800 border-white hover:border-transparent hover:text-gray-700 hover:bg-gray-200 mt-4 lg:mt-0">Download</a>-->
            </nav>
        </div>
</template>

<script>
    export default {
        name: 'navbar-mobile',
        props: ['asset', 'user'],
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
            axios.get('/staff/22/workflowBackendCounts')
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

