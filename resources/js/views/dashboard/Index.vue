<template>
    <div class="content">
        <div class="container-fluid">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="page-title">Dashboard</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Welcome to Self Checker Dashboard</li>
                        </ol>
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

