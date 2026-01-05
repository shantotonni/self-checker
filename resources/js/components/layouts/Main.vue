<template>
    <div id="wrapper">
        <Navbar/>
        <Sidebar/>
        <div class="content-page">
            <router-view></router-view>
            <Footer/>
        </div>
    </div>
</template>
<script>
import Navbar from './Navbar'
import Sidebar from './Sidebar'
import Footer from './Footer'

export default {
    components: {
        Navbar,
        Sidebar,
        Footer
    }
}
</script>
