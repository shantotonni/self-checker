<template>
    <div class="left side-menu">
        <div class="slimscroll-menu" id="remove-scroll">
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">
                    <li class="menu-title">Main</li>

                    <li>
                        <router-link style="font-size: 12px" to="/price-survey/business-wise-product" class="waves-effect"><i class="ti-calendar"></i><span> Business Wise Product</span></router-link>
                        <router-link style="font-size: 12px" to="/price-survey/mokam-list" class="waves-effect"><i class="ti-calendar"></i><span> Mokam</span></router-link>
                        <router-link style="font-size: 12px" to="/price-survey/product-price-list" class="waves-effect"><i class="ti-calendar"></i><span> Product Price</span></router-link>
                        <a style="font-size: 12px" href="#" class="waves-effect"><i class="ti-calendar"></i><span> Daily Food Price Report (R.P)</span></a>
                        <a style="font-size: 12px" href="#" class="waves-effect"><i class="ti-calendar"></i><span> Daily Food Price Report (W.P)</span></a>
                        <router-link style="font-size: 12px" to="/price-survey/survey-chart" class="waves-effect"><i class="ti-calendar"></i><span> Survey Chart</span></router-link>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            user_menu:[]
        }
    },
    mounted() {
       setTimeout(()=>{
           $("#side-menu").metisMenu();
       },1000)

        //this.$store.dispatch('getAllUserMenu');
    },
    computed:{
        // getAllUserMenuForSidebar(){
        //     return this.$store.getters.getAllUserMenu;
        // },
    },
    methods:{
        //
    }
}
</script>
