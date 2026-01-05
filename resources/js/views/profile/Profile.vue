<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['User Profile']"/>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="datatable" v-if="!isLoading">
                            <div class="card-body">
                                <div class="col-md-12">
                                    <form @submit.prevent="updateProfile">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>User Name</label>
                                                    <input type="text" name="name" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                                    <div class="error" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>UserID</label>
                                                    <input type="text" readonly name="username" v-model="form.username" class="form-control" :class="{ 'is-invalid': form.errors.has('username') }">
                                                    <div class="error" v-if="form.errors.has('username')" v-html="form.errors.get('username')" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Designation</label>
                                                    <input type="text" name="designation" v-model="form.designation" class="form-control" :class="{ 'is-invalid': form.errors.has('designation') }">
                                                    <div class="error" v-if="form.errors.has('designation')" v-html="form.errors.get('designation')" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Mobile</label>
                                                    <input type="text" name="mobile" v-model="form.mobile" class="form-control" :class="{ 'is-invalid': form.errors.has('mobile') }">
                                                    <div class="error" v-if="form.errors.has('mobile')" v-html="form.errors.get('mobile')" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" name="email" v-model="form.email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                                    <div class="error" v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <skeleton-loader :row="14"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "Profile",
    data() {
        return {
            user: {},
            isLoading: false,
            form: new Form({
                id:'',
                name:'',
                username: '',
                email:'',
                designation: '',
                mobile: '',
            }),
        }
    },
    mounted() {
        document.title = 'User Profile | Harvester';
        this.getUserByUserId();
    },
    methods: {
        getUserByUserId(){
            this.isLoading = true;
            axios.get('/api/user-by-user-id').then((response)=>{
                this.form.fill(response.data.user);
                this.isLoading = false;
            }).catch((error)=>{

            })
        },
        updateProfile(){
            this.form.busy = true;
            this.form.post("/api/user-profile-update/").then(response => {
                console.log(response)
                this.$toaster.success('Profile Successfully Updated');
            }).catch(e => {
                this.isLoading = false;
            });
        },
    },
}
</script>

<style scoped>

</style>
