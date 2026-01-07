<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['User List']"/>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="datatable" v-if="!isLoading">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input v-model="query" type="text" class="form-control" placeholder="Search">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-success btn-sm" @click="createUser">
                                            <i class="fas fa-plus"></i>
                                            Add
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                            <i class="fas fa-sync"></i>
<!--                                            Reload-->
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Name</th>
                                            <th class="text-left">User ID</th>
                                            <th class="text-left">Role</th>
                                            <th class="text-left">Mobile No.</th>
                                            <th class="text-left">Email</th>
                                            <th class="text-left">Status</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(user, i) in users" :key="user.id" v-if="users.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ user.name }}</td>
                                            <td class="text-left">{{ user.username }}</td>
                                            <td class="text-left">{{ user.role_name }}</td>
                                            <td class="text-right">{{ user.mobile }}</td>
                                            <td class="text-left">{{ user.email }}</td>
                                            <td class="text-left">
                                                <span class="badge badge-success" v-if="user.Active == 1">Active</span>
                                                <span class="badge badge-success" v-else>InActive</span>
                                            </td>
                                            <td class="text-left">
                                                <button @click="edit(user)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
<!--                                                <button @click="destroy(user.id)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllUser() : searchData()"
                                    ></pagination>
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
        <!--  Modal content for the above example -->
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="col-md-12">
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
                                            <label>User Name Bangla</label>
                                            <input type="text" name="name_bn" v-model="form.name_bn" class="form-control" :class="{ 'is-invalid': form.errors.has('name_bn') }">
                                            <div class="error" v-if="form.errors.has('name_bn')" v-html="form.errors.get('name_bn')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>UserID</label>
                                            <input type="text" name="username" v-model="form.username" class="form-control" :class="{ 'is-invalid': form.errors.has('username') }">
                                            <div class="error" v-if="form.errors.has('username')" v-html="form.errors.get('username')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mobile No.</label>
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
                                    <div class="col-md-6" v-if="!editMode">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="Password" v-model="form.Password" class="form-control" :class="{ 'is-invalid': form.errors.has('Password') }">
                                            <div class="error" v-if="form.errors.has('Password')" v-html="form.errors.get('Password')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Role</label>
                                            <select name="role_id" id="role_id" class="form-control" v-model="form.role_id" :class="{ 'is-invalid': form.errors.has('role_id') }">
                                                <option disabled value="">Select Role</option>
                                                <option :value="role.id" v-for="(role , index) in roles" :key="index">{{ role.name }}</option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('role_id')" v-html="form.errors.get('role_id')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "List",
    data() {
        return {
            users: [],
            roles: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id:'',
                name:'',
                username: '',
                role_id: '',
                role_name: '',
                email:'',
                mobile: '',
            }),
        }
    },
    watch: {
        query: function(newQ, old) {
            if (newQ === "") {
                this.getAllUser();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'User List | Harvester';
        this.getAllUser();
    },
    methods: {
        getAllUser(){
            this.isLoading = true;
            axios.get('/api/user?page='+ this.pagination.current_page).then((response)=>{
                this.users = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error)=>{

            })
        },
        searchData(){
            axios.get("/api/search/user/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.users = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload(){
            this.getAllUser();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal(){
            $("#userModal").modal("hide");
        },
        createUser(){
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#userModal").modal("show");
            this.getAllRole();
        },
        getAllRole(){
            axios.get('/api/role?page='+ 1).then((response)=>{
                this.roles = response.data.data;
            }).catch((error)=>{

            })
        },
        store(){
            this.form.busy = true;
            this.form.post("/api/user").then(response => {
                $("#userModal").modal("hide");
                this.getAllUser();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(user) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(user);
            $("#userModal").modal("show");
            this.getAllRole();
        },
        update(){
            this.form.busy = true;
            this.form.put("/api/user/" + this.form.id).then(response => {
                $("#userModal").modal("hide");
                this.getAllUser();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        changeImage(event) {
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = event => {
                this.form.image = event.target.result;
            };
            reader.readAsDataURL(file);
        },
        showImage() {
            let img = this.form.image;
            if (img.length > 100) {
                return this.form.image;
            } else {
                return window.location.origin + "/harvester/public/images/user/" + this.form.image;
            }
        },
        tableImage(image) {
            return window.location.origin + "/harvester/public/images/user/" + image;
        },
        destroy(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('api/user/'+ id).then((response)=>{
                        this.getAllUser();
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    })
                }
            })
        }
    },
}
</script>

<style scoped>

</style>
