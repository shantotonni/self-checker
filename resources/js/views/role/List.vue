<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Role List']"/>
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
<!--                                        <button type="button" class="btn btn-success btn-sm" @click="createRole">-->
<!--                                            <i class="fas fa-plus"></i>-->
<!--                                            Add Role-->
<!--                                        </button>-->
                                        <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                            <i class="fas fa-sync"></i>
                                            Reload
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                            <tr>
                                                <th class="text-left">SN</th>
                                                <th class="text-left">Role Name</th>
                                                <th class="text-left">Created At</th>
                                                <th class="text-left">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(role, i) in roles" :key="role.id" v-if="roles.length">
                                                <th class="text-center" scope="row">{{ ++i }}</th>
                                                <td class="text-left">{{ role.name }}</td>
                                                <td class="text-left">{{ role.created_at }}</td>
                                                <td class="text-left">
                                                    <button @click="edit(role)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                                                    <button @click="destroy(role.id)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllRole() : searchData()"
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
        <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Role Name</label>
                                            <input type="text" name="name" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                            <div class="error" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Role</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
</template>

<script>

export default {
    name: "List",
    data() {
        return {
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
                created_at:''
            }),
        }
    },
    watch: {
        query: function(newQ, old) {
            if (newQ === "") {
                this.getAllRole();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Role List | Harvester';
        this.getAllRole();
    },
    methods: {
        getAllRole(){
            this.isLoading = true;
            axios.get('/api/role?page='+ this.pagination.current_page).then((response)=>{
                console.log(response)
                this.roles = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error)=>{

            })
        },
        searchData(){
            axios.get("/api/search/role/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                    this.roles = response.data.data;
                    this.pagination = response.data.meta;
                }).catch(e => {
                    this.isLoading = false;
                });
        },
        reload(){
            this.getAllRole();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal(){
            $("#roleModal").modal("hide");
        },
        createRole(){
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#roleModal").modal("show");
        },
        store(){
            this.form.busy = true;
            this.form.post("/api/role").then(response => {
                    $("#roleModal").modal("hide");
                    this.getAllRole();
                }).catch(e => {
                    this.isLoading = false;
                });
        },
        edit(role) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(role);
            $("#roleModal").modal("show");
        },
        update(){
            this.form.busy = true;
            this.form.put("/api/role/" + this.form.id).then(response => {
                    $("#roleModal").modal("hide");
                    this.getAllRole();
                }).catch(e => {
                    this.isLoading = false;
                });
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
                    axios.delete('api/role/'+ id).then((response)=>{
                        this.getAllRole();
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
