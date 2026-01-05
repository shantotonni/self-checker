<template>
    <div>
        {{ row.point_details.length > 0 ? parseFloat(row.point_details[0].totalPoint) : 0 }}
    </div>
</template>
<script>
export default {
    props: ['row']
}
</script>
