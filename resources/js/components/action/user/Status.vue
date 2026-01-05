<template>
    <div>
        <span class="badge badge-pill" :class="row.is_active=== '1' ?'badge-success':'badge-danger'">{{row.is_active=== '1' ?'Active':'Inactive'}}</span>
    </div>
</template>
<script>
export default {
    props:['row']
}
</script>
