<template>
    <div class="content">
        <div class="container-fluid">
            <div id='main'>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-md-12">
                        <div style="text-align: center;background: #3C96C6;padding-left: 12px;padding-right: 12px;padding-top: 1px;padding-bottom: 1px;color: white;text-transform: uppercase;">
                            <h4 style="font-size: 30px">Harvester Dashboard</h4>
                        </div>

                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-md-4">
                        <div style="text-align: center">
                            <img style="height: 65px;width: 150px" :src="'images/brand/himinoso.png'" alt="">
                        </div>
                    </div>
                    <div class="col-md-4" >
                        <div style="text-align: center">
                            <img style="height: 65px;width: 150px" :src="'images/brand/Yamaha.png'" alt="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="text-align: center">
                            <img style="width: 220px" :src="'images/brand/YORO.png'" alt="">
                        </div>
                    </div>
                </div>
                <div class="chart-area">
                    <div class="chart-content">
                        <p class="chart-title">Customer Satisfaction Index</p>
                        <div class="circle"><span class="percentage"  id="chart1">100%</span></div>
                    </div>
                </div>

                <div class="chart-area">
                    <div class="chart-content">
                        <p class="chart-title">Service Ratio</p>
                        <div class="circle"><span class="percentage"  id="chart2">100%</span></div>
                    </div>
                </div>

<!--                <div class="chart-area">-->
<!--&lt;!&ndash;                    <div class="chart-content">&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        <p class="chart-title">Spare Parts Supply Ratio</p>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                        <div class="circle"><span class="percentage"  id="chart3">100%</span></div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                    </div>&ndash;&gt;-->
<!--                </div>-->

                <div class="chart-area">
                    <div class="chart-content">
                        <p class="chart-title">Within 12 Hours Service</p>
                        <div class="circle"><span class="percentage"  id="chart4">100%</span></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="card mini-stat text-white" style="margin-top: 20px;">
                            <div class="card-header" style="background-color: white; color:black;">
                                <h5>Last 12 Months Service Ratio</h5>
                            </div>
                            <div class="card-body" style="padding: 10px">
                                <barChart v-if="isLoading" :labels="labels" :datasets="datasets" />
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card" style="margin-top: 20px;">
                            <div class="card-header" style="background-color: white;color:black;">
                                <h5>Weekly Performance LeaderBoard</h5>
                            </div>
                            <div class="card-body">
                                <div class="row" style="background-color: #DFE6F6;margin-bottom:10px;" v-for="(technician, i) in technicians" :key="technician.technician_id" v-if="technicians.length">
                                    <div class="col-lg-2" style="padding-top: 10px;">
                                        <img style="border-radius: 40px;height: 60px;width:60px;" :src="`images/user/${technician.Image}`" alt="">
                                    </div>
                                    <div class="col-lg-10" style="margin-left: -20px;padding-top: 10px;padding-bottom:2px;">
                                        <p class="" style="font-size: 15px;font-weight:bold;line-height:0;padding-top:10px;">{{ technician.name }}</p>
                                        <p style="line-height:0;" v-if="Number(technician.Rating) === 5">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                        </p>
                                        <p style="line-height:0;" v-if="Number(technician.Rating) === 4">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                        </p>
                                        <p style="line-height:0;" v-if="Number(technician.Rating) === 3">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                        </p>
                                        <p style="line-height:0;" v-if="Number(technician.Rating) === 2">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star "></span>
                                            <span class="fa fa-star "></span>
                                            <span class="fa fa-star"></span>
                                        </p>
                                        <p style="line-height:0;" v-if="Number(technician.Rating) === 1">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
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
            technicians: '',
            datasets:[
                {
                    label: "12 Months Service Request Chart",
                    data: [],
                    backgroundColor: "#376CEE",
                    hoverBackgroundColor: "rgba(38, 198, 218, 1)",
                    hoverBorderWidth: 0,
                }],
            isLoading : false
        }
    },
    mounted() {
        document.title = 'Admin Dashboard | Harvester';
        this.getAllDashboardData();
    },
    methods: {
        getAllDashboardData(){
            axios.get('/api/get-all-admin-dashboard-data').then((response)=>{
                console.log(response.data.data)
                this.technicians = response.data.data.technician
                this.allData = response.data.data;
                this.labels = response.data.data.label;
                this.datasets[0].data = response.data.data.value;
                $('#chart1').text(this.allData.csi);
                $('#chart2').text(this.allData.service);
                $('#chart3').text(this.allData.spareParts);
                $('#chart4').text(this.allData.twelveHour);
                this.isLoading = true;
            }).catch((error)=>{

            })
        },
    }
}
</script>

<style>
  html, body {
      width: 100%;
      height: 100%;
      margin: 0;
      background: #e9e9e9;
  }
  #main {
      padding: 20px;
  }
  #main:after {
      display: block;
      content: ' ';
      clear: both;
  }
  .banner-area {
      width: 100%;
      float: left;
      background: #409eff;
  }
  /*.banner-area .banner-content {*/
  /*    width: 99%;*/
  /*    background: #e9e9e9;*/
  /*    padding-top: 6px;*/
  /*    padding-left: 6px;*/
  /*}*/
  .banner-area .banner-content img {
      width: 100%;
      height: auto;
      float: left;
  }
  .chart-content {
        width: 98%;
        background: #ffffff;
        float: left;
        height: 200px;
        padding-top: 0px;
        background-image: linear-gradient(45deg, #f7f7f921, #46bfff);
        /* padding-left: 6px; */
    }

  .chart-area {
      width: 33.333%;
      height: 200px;
      float: left;
      background: #e9e9e9;
  }
  .chart{
      width: 100%;
      height: 150px;
      margin: 0 auto;
      background-color: #fff;
      text-align: center;
      padding-top: 10px;
      font-size: 40px;
  }
  .chart-title {
      padding:5px;
      margin: 0px;
      font-size: 16px;
      font-weight: normal;
      height: 35px;
      text-align: center;
      background: #397aca;
      color: white;
      text-transform: uppercase
  }
  h3{
      font-size: 16px!important;
  }
  .chart_body{
      margin-top: 20px;
  }
  .line-chart-area {
      width: 100%;
      float: left;
      background: #fefcff;
      margin-top: 20px;
  }
  @media (min-width: 768px) {
      .col-md-6 {
          -ms-flex: 0 0 50%;
          flex: 0 0 50%;
          /* max-width: 50%; */
      }
  }
  .checked {
      color: orange;
  }
  .bg-info {
      border-radius: 40px!important;
  }
  .info-box {
      background: #d8e6f7!important;
  }
  .info-box .info-box-icon {
      width: 60px;
  }
  .image_size{
      height: 40px;
      width: 100px;
      margin: 0 auto;
      display: block;
  }
  .info-box .info-box-content {
      -ms-flex: 1;
      flex: 1;
      padding: 0px 0px;
  }

  /* for status chart */
   .percentage {
        position: absolute;
        top: 30%;
        left: 0;
        right: 0;
        margin: auto;
        text-align: center;
        color: #ffffff;
        font-weight: bold;
        font-size: 25px;
    }

    .circle {
        height:80px;
        width:80px;
        border-radius:50%;
        background-color: #000dff;

        position:relative;
        top:80px;
        left:51%;

        -webkit-transition:height .25s ease, width .25s ease;
        transition:height .25s ease, width .25s ease;

        -webkit-transform:translate(-50%,-50%);
        transform:translate(-50%,-50%);
    }

    .circle:hover{
        height:120px;
        width:120px;
    }

    .circle:before,
    .circle:after {
        content:'';
        display:block;
        position:absolute;
        top:0; right:0; bottom:0; left:0;
        border-radius:50%;
        border:1px solid #5d58f1;
    }

    .circle:before {
        -webkit-animation: ripple 2s linear infinite;
        animation: ripple 2s linear infinite;
    }
    .circle:after {
        -webkit-animation: ripple 2s linear 1s infinite;
        animation: ripple 2s linear 1s infinite;
    }

    .circle:hover:before,
    .circle:hover:after {
        -webkit-animation: none;
        animation: none;
    }

    @-webkit-keyframes ripple{
        0% {-webkit-transform:scale(1); }
        75% {-webkit-transform:scale(1.75); opacity:1;}
        100% {-webkit-transform:scale(2); opacity:0;}
    }

    @keyframes ripple{
        0% {transform:scale(1); }
        75% {transform:scale(1.75); opacity:1;}
        100% {transform:scale(2); opacity:0;}
    }
    /* footer */
    .footer {
        position: static;
    }
</style>
