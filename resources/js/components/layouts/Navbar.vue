<template>
    <div class="topbar">
        <div class="topbar-left">
            <router-link :to="{name:'Dashboard'}" class="logo" style="line-height: 67px;color: white">
<!--                <span><img style="width: 200px" :src="`${mainOrigin}images/PowerEX.png`" alt="logo"/> </span><i><img-->
<!--                :src="`assets/images/aci.jpeg`" style="border-radius: 5%;" alt="" height="40"/></i>-->
               Price Survey
            </router-link>
        </div>
        <nav class="navbar-custom">
            <ul class="navbar-right list-inline float-right mb-0">
                 <!-- full screen -->
                <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
                    <a class="nav-link waves-effect" href="#" id="btn-fullscreen"><i class="mdi mdi-fullscreen noti-icon"></i></a>
                </li>
                <li class="dropdown notification-list list-inline-item">
                    <div class="dropdown notification-list nav-pro-img">
                        <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown"
                           href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img :src="`${mainOrigin}assets/images/users/user-4.jpg`" alt="user" class="rounded-circle"/>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                            <router-link class="dropdown-item" :to="{name : 'Profile'}"><i class="mdi mdi-account-circle m-r-5"></i>Profile</router-link>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" @click="logout" href="#"><i class="mdi mdi-power text-danger"></i>Logout</a>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="list-inline menu-left mb-0">
                <li class="float-left">
                    <button @click="toggleSidebar" class="button-menu-mobile open-left waves-effect"><i class="mdi mdi-menu"></i></button>
                </li>
            </ul>
        </nav>
    </div>
</template>
<script>

export default {
    name: 'Navbar',
    methods: {
        toggleSidebar(e) {
            $("body").toggleClass("enlarged")
        },
        logout() {
            localStorage.setItem("token", "");
            this.$router.push({name: 'Login'});
            this.$toaster.success('Successfully Logged Out')
        }
    }
}
</script>
