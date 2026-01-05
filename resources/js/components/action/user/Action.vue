<template>
    <div>
<!--        <button class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button>-->
        <router-link :to="`user-edit/${row.id}`" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></router-link>
<!--        <router-link :to="`user-menu-permission/${row.ID}`" class="btn btn-info btn-sm"><i class="mdi mdi-access-point-network"></i>User Menu Permission</router-link>-->
<!--        <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>-->
    </div>
</template>
<script>
export default {
    props:['row']
}

</script>
