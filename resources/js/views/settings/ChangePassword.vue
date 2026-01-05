<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Change Password']"/>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="repeater" @submit.prevent="changePassword" @keydown="form.onKeydown($event)">
                                <div data-repeater-list="group-a">
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label for="previous_password">Previous Password</label>
                                            <input type="password" class="form-control" id="previous_password" :class="{ 'is-invalid': form.errors.has('previous_password') }" v-model="form.previous_password" name="previous_password" placeholder="Previous Password">
                                            <div class="error" v-if="form.errors.has('previous_password')" v-html="form.errors.get('previous_password')" />
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label for="Password">New Password</label>
                                            <input type="password" class="form-control" id="password" :class="{ 'is-invalid': form.errors.has('password') }" v-model="form.password" name="password" placeholder="New Password">
                                            <div class="error" v-if="form.errors.has('password')" v-html="form.errors.get('password')" />
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label for="password_confirmation">Confirm password</label>
                                            <input type="password" class="form-control" id="password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" v-model="form.password_confirmation" name="password_confirmation" placeholder="password confirmation">
                                            <div class="error" v-if="form.errors.has('password_confirmation')" v-html="form.errors.get('password_confirmation')" />
                                        </div>
                                        <div class="form-group col-lg-2" style="margin-top: 26px">
                                            <button type="submit" class="btn btn-success mo-mt-2 float-left" value="Add Menu">Change Password</button>
                                        </div>
                                    </div>
                                </div>
                                <!--                                <button type="submit" class="btn btn-success mo-mt-2 float-right" value="Add Menu">Add Menu</button>-->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
document.title = 'Change Password | Diesel Engine';
export default {
    name: "Change Password",
    data() {
        return {
            form: new Form({
                previous_password: '',
                password: '',
                password_confirmation: '',
            }),
        }
    },
    mounted() {
        document.title = 'Change Password | Diesel Engine';
    },
    methods: {
        changePassword(){
            this.form.post('/api/change-password').then((response)=>{
                console.log(response)
                this.$toaster.success(response.data.message);
            }).catch((error)=>{
                this.$toaster.error('Something went wrong')
            })
        },
    }
}
</script>

<style scoped>

</style>
