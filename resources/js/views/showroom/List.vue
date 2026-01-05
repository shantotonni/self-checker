<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Showroom List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" @click="createShowroom">
                                <i class="fas fa-plus"></i>
                                Add Showroom
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                <i class="fas fa-sync"></i>
                                Reload
                            </button>
                        </div>
                    </div>
                </div>
            </breadcrumb>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="datatable" v-if="!isLoading">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select name="" id="" v-model="area_id" class="form-control">
                                                        <option disabled value="">Select Area</option>
                                                        <option :value="area.id" v-for="(area , index) in areas" :key="index">{{ area.name_bn }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <button type="submit" @click="getAllShowroom" class="btn btn-success"><i class="mdi mdi-filter"></i>Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <input v-model="query" type="text" class="form-control" placeholder="Search by showroom name">
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Area</th>
                                            <th class="text-left">Responsible Person</th>
                                            <th class="text-left">Showroom Name</th>
                                            <th class="text-left">Address</th>
                                            <th class="text-left">Mobile</th>
                                            <th class="text-left">Lat</th>
                                            <th class="text-left">Long</th>
                                            <th class="text-left">Image</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(showroom, i) in showrooms" :key="showroom.id"
                                            v-if="showrooms.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ showroom.area_name_bn }}</td>
                                            <td class="text-left">{{ showroom.responsible_person }}</td>
                                            <td class="text-left">{{ showroom.showroom_name }}</td>
                                            <td class="text-left">{{ showroom.address }}</td>
                                            <td class="text-right">{{ showroom.mobile }}</td>
                                            <td class="text-right">{{ showroom.lat }}</td>
                                            <td class="text-right">{{ showroom.long }}</td>
                                            <td class="text-left">
                                                <img v-if="showroom.image" height="40" width="40"
                                                     :src="tableImage(showroom.image)" alt="">
                                            </td>
                                            <td class="text-left">
                                                <button @click="edit(showroom)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button @click="destroy(showroom.id)"
                                                        class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                                </button>
                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllShowroom() : searchData()"
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
        <div class="modal fade" id="showroomModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Showroom </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">
                            ×
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Area</label>
                                            <select name="text" id="area_id" class="form-control" v-model="form.area_id"
                                                    :class="{ 'is-invalid': form.errors.has('area_id') }">
                                                <option disabled value="">Select Area</option>
                                                <option :value="area.id" v-for="(area , index) in areas" :key="index">{{ area.name_bn}}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('area_id')"
                                                 v-html="form.errors.get('area_id')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Responsible Person</label>
                                            <input type="text" name="responsible_person"
                                                   v-model="form.responsible_person"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('responsible_person') }">
                                            <div class="error" v-if="form.errors.has('responsible_person')"
                                                 v-html="form.errors.get('responsible_person')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Showroom Name</label>
                                            <input type="text" name="showroom_name"
                                                   v-model="form.showroom_name"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('showroom_name') }">
                                            <div class="error" v-if="form.errors.has('showroom_name')"
                                                 v-html="form.errors.get('showroom_name')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mobile</label>
                                            <input type="text" name="mobile" v-model="form.mobile"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('mobile') }">
                                            <div class="error" v-if="form.errors.has('mobile')"
                                                 v-html="form.errors.get('mobile')"/>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="Name" v-model="form.address"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('address') }">
                                            <div class="error" v-if="form.errors.has('address')"
                                                 v-html="form.errors.get('address')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>lat</label>
                                            <input type="text" name="lat" v-model="form.lat"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('lat') }">
                                            <div class="error" v-if="form.errors.has('lat')"
                                                 v-html="form.errors.get('lat')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>long</label>
                                            <input type="text" name="long" v-model="form.long"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('long') }">
                                            <div class="error" v-if="form.errors.has('long')"
                                                 v-html="form.errors.get('long')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input @change="changeImage($event)" type="file" name="image" class="form-control" :class="{ 'is-invalid': form.errors.has('image') }">
                                            <div class="error" v-if="form.errors.has('image')" v-html="form.errors.get('image')" />
                                            <img v-if="form.image" :src="showImage(form.image)" alt="" height="40px" width="40px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">
                                Close
                            </button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                {{ editMode ? "Update" : "Create" }} Showroom
                            </button>
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
            showrooms: [],
            areas: [],
            pagination: {
                current_page: 1
            },
            query: "",
            area_id:'',
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                area_id: '',
                address: '',
                responsible_person: '',
                showroom_name: '',
                mobile: '',
                lat: '',
                long: '',
                image: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllShowroom();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Showroom List | Harvester';
        this.getAllShowroom();
        this.getAllAreas();
    },
    methods: {
        getAllShowroom() {
            this.isLoading = true;
            axios.get('/api/showroom-list?page=' + this.pagination.current_page +"&area_id="+ this.area_id ).then((response) => {
                // console.log('data', response.data.data)
                this.showrooms = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/showroom-list/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.showrooms = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllShowroom();
            this.area_id="";
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#showroomModal").modal("hide");
        },
        createShowroom() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#showroomModal").modal("show");
            this.getAllAreas();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/showroom-list").then(response => {
                $("#showroomModal").modal("hide");
                this.getAllShowroom();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(showroom) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(showroom);

            this.getAllAreas();
            $("#showroomModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/showroom-list/" + this.form.id).then(response => {
                $("#showroomModal").modal("hide");
                this.getAllShowroom();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        getAllAreas() {
            axios.get('/api/get-all-areas').then((response) => {
              console.log(response)
                this.areas = response.data.areas;
            }).catch((error) => {

            })
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
                return window.location.origin + "/harvester/public/images/showroom/" + this.form.image;
            }
        },
        tableImage(image) {
            return window.location.origin + "/harvester/public/images/showroom/" + image;
        },
        destroy(id) {
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
                    axios.delete('api/showroom-list/' + id).then((response) => {
                        this.getAllShowroom();
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
