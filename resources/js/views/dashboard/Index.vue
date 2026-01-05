<template>
    <div class="content">
        <div class="container-fluid">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="page-title">Dashboard</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Welcome to Price Survey Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4"><img :src="`${mainOrigin}assets/images/services-icon/01.png`" alt=""/></div>
                                <h5 class="font-12 text-uppercase mt-0 text-white-50"></h5>
                                <h4 class="font-500"> <i class="mdi mdi-arrow-up text-success ml-2"></i></h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
<!--                                    <router-link :to="{name : 'GeneratorInfoList'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>-->
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4"><img :src="`${mainOrigin}assets/images/services-icon/02.png`" alt=""/></div>
                                <h5 class="font-12 text-uppercase mt-0 text-white-50"></h5>
                                <h4 class="font-500"> <i class="mdi mdi-arrow-down text-danger ml-2"></i>
                                </h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
<!--                                    <router-link :to="{name : 'DeliveryInfoList'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>-->
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4"><img :src="`${mainOrigin}assets/images/services-icon/03.png`" alt=""/></div>
                                <h5 class="font-12 text-uppercase mt-0 text-white-50"></h5>
                                <h4 class="font-500"> <i class="mdi mdi-arrow-up text-success ml-2"></i>
                                </h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
<!--                                    <router-link :to="{name : 'DeliveryInfoList'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>-->
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4"><img :src="`${mainOrigin}assets/images/services-icon/04.png`" alt=""/></div>
                                <h5 class="font-12 text-uppercase mt-0 text-white-50"></h5>
                                <h4 class="font-500"><i class="mdi mdi-arrow-up text-success ml-2"></i>
                                </h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
<!--                                    <router-link :to="{name : 'PendingServiceRequestList'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>-->
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body" style="padding: 10px">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4"><img :src="`${mainOrigin}assets/images/services-icon/04.png`" alt=""/></div>
                                <h5 class="font-12 text-uppercase mt-0 text-white-50"></h5>
                                <h4 class="font-500"><i class="mdi mdi-arrow-up text-success ml-2"></i>
                                </h4>
                            </div>
                            <div class="pt-2">
                                <div class="float-right">
<!--                                    <router-link :to="{name : 'CompletedServiceRequestList'}" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></router-link>-->
                                </div>
                                <p class="text-white-50 mb-0">Go</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="card mini-stat text-white">
                        <div class="card-body" style="padding: 10px">
                            <barChart v-if="isLoading" :labels="labels" :datasets="datasets" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "Dashboard",
    data() {
        return {
            allData: [],
            labels:[],
            datasets:[
                {
                    label: "12 Months Service Request Chart",
                    data: [],
                    backgroundColor: "rgba(38, 198, 218, 1)",
                    hoverBackgroundColor: "#7E57C2",
                    hoverBorderWidth: 0,
                }],
            isLoading : false
        }
    },
    mounted() {
        document.title = 'Dashboard | Price Survey';
        //this.getAllDashboardData();
    },
    methods: {
        getAllDashboardData(){
            axios.get('/api/get-all-dashboard-data').then((response)=>{
                console.log(response)
                this.allData = response.data.data;
                this.labels = response.data.data.label;
                this.datasets[0].data = response.data.data.value;
                this.isLoading = true;
            }).catch((error)=>{

            })
        },
    }
}
</script>

