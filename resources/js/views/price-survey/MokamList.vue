<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Mokam List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" @click="createMokam">
                                <i class="fas fa-plus"></i>
                                Add Mokam
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
                                                    <select v-model="filterDistrict" class="form-control">
                                                        <option disabled value="">Select District</option>
                                                        <option :value="district.districtCode" v-for="(district, index) in districts" :key="index">
                                                            {{ district.districtName }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select v-model="filterActive" class="form-control">
                                                        <option disabled value="">Select Status</option>
                                                        <option value="Y">Active</option>
                                                        <option value="N">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <button type="button" @click="getAllMokams" class="btn btn-success">
                                                    <i class="mdi mdi-filter"></i> Filter
                                                </button>
                                                <button type="button" @click="clearFilter" class="btn btn-secondary">
                                                    <i class="mdi mdi-close"></i> Clear
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <input v-model="query" type="text" class="form-control" placeholder="Search by mokam name">
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-center">SN</th>
                                            <th class="text-left">District</th>
                                            <th class="text-left">Mokam Name</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Created At</th>
                                            <th class="text-center">Updated At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(mokam, i) in mokams" :key="mokam.mokamId" v-if="mokams.length">
                                            <td class="text-center">{{ getSerialNumber(i) }}</td>
                                            <td class="text-left">{{ mokam.districtName || mokam.districtCode }}</td>
                                            <td class="text-left">{{ mokam.mokamName }}</td>
                                            <td class="text-center">
                                                    <span :class="mokam.active ? 'badge badge-success' : 'badge badge-danger'">
                                                        {{ mokam.active ? 'Active' : 'Inactive' }}
                                                    </span>
                                            </td>
                                            <td class="text-center">{{ formatDate(mokam.createdAt) }}</td>
                                            <td class="text-center">{{ formatDate(mokam.updatedAt) }}</td>
                                            <td class="text-center">
                                                <button @click="edit(mokam)" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                                <button @click="toggleStatus(mokam)" class="btn btn-warning btn-sm" title="Toggle Status">
                                                    <i :class="mokam.active ? 'fas fa-ban' : 'fas fa-check'"></i>
                                                </button>
                                                <button @click="destroy(mokam.mokamId)" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!mokams.length">
                                            <td colspan="7" class="text-center">No data available</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllMokams() : searchData()"
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

        <!-- Modal for Create/Edit -->
        <div class="modal fade" id="mokamModal" tabindex="-1" role="dialog" aria-labelledby="mokamModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mokamModalLabel">{{ editMode ? "Edit" : "Add" }} Mokam</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">
                            ×
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>District <span class="text-danger">*</span></label>
                                        <select v-model="form.districtCode"
                                                class="form-control"
                                                :class="{ 'is-invalid': form.errors.has('districtCode') }">
                                            <option disabled value="">Select District</option>
                                            <option :value="district.districtCode" v-for="(district, index) in districts" :key="index">
                                                {{ district.districtName }}
                                            </option>
                                        </select>
                                        <div class="error" v-if="form.errors.has('districtCode')" v-html="form.errors.get('districtCode')"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Mokam Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                               v-model="form.mokamName"
                                               class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('mokamName') }"
                                               placeholder="Enter mokam name">
                                        <div class="error" v-if="form.errors.has('mokamName')" v-html="form.errors.get('mokamName')"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select v-model="form.active" class="form-control" :class="{ 'is-invalid': form.errors.has('active') }">
                                            <option :value="true">Active</option>
                                            <option :value="false">Inactive</option>
                                        </select>
                                        <div class="error" v-if="form.errors.has('active')" v-html="form.errors.get('active')"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">
                                Close
                            </button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                <i v-if="form.busy" class="fas fa-spinner fa-spin"></i>
                                {{ editMode ? "Update" : "Create" }} Mokam
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
    name: "MokamList",
    data() {
        return {
            mokams: [],
            districts: [],
            pagination: {
                current_page: 1,
                last_page: 1,
                per_page: 15,
                total: 0
            },
            query: "",
            filterDistrict: "",
            filterActive: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                mokamId: '',
                districtCode: '',
                mokamName: '',
                active: true
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllMokams();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Mokam List | Group Expense';
        this.getAllMokams();
        this.getDistricts();
    },
    methods: {
        getAllMokams() {
            this.isLoading = true;
            let params = new URLSearchParams();
            params.append('page', this.pagination.current_page);

            if (this.filterDistrict) {
                params.append('district_code', this.filterDistrict);
            }
            if (this.filterActive !== "") {
                params.append('active', this.filterActive);
            }

            axios.get('/api/mokams?' + params.toString())
                .then((response) => {
                    this.mokams = response.data.data;
                    this.pagination = response.data.meta;
                    this.isLoading = false;
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.$toaster.error('Failed to load mokams');
                    console.error(error);
                });
        },

        searchData() {
            this.isLoading = true;
            axios.get("/api/mokams/search/" + this.query + "?page=" + this.pagination.current_page)
                .then(response => {
                    this.mokams = response.data.data;
                    this.pagination = response.data.meta;
                    this.isLoading = false;
                })
                .catch(e => {
                    this.isLoading = false;
                    console.error(e);
                });
        },

        getDistricts() {
            axios.get('/api/mokams/districts')
                .then((response) => {
                    this.districts = response.data.districts;
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        reload() {
            this.clearFilter();
            this.getAllMokams();
            this.$toaster.success('Data Successfully Refreshed');
        },

        clearFilter() {
            this.filterDistrict = "";
            this.filterActive = "";
            this.query = "";
            this.pagination.current_page = 1;
        },

        closeModal() {
            $("#mokamModal").modal("hide");
        },

        createMokam() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            this.form.active = true;
            $("#mokamModal").modal("show");
        },

        store() {
            this.form.busy = true;
            this.form.post("/api/mokams")
                .then(response => {
                    $("#mokamModal").modal("hide");
                    this.getAllMokams();
                    this.$toaster.success('Mokam created successfully');
                })
                .catch(e => {
                    this.form.busy = false;
                    console.error(e);
                });
        },

        edit(mokam) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill({
                mokamId: mokam.mokamId,
                districtCode: mokam.districtCode,
                mokamName: mokam.mokamName,
                active: mokam.active
            });
            $("#mokamModal").modal("show");
        },

        update() {
            this.form.busy = true;
            this.form.put("/api/mokams/" + this.form.mokamId)
                .then(response => {
                    $("#mokamModal").modal("hide");
                    this.getAllMokams();
                    this.$toaster.success('Mokam updated successfully');
                })
                .catch(e => {
                    this.form.busy = false;
                    console.error(e);
                });
        },

        toggleStatus(mokam) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to ${mokam.active ? 'deactivate' : 'activate'} this mokam?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, do it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.patch('/api/mokams/' + mokam.mokamId + '/toggle-status')
                        .then((response) => {
                            this.getAllMokams();
                            Swal.fire('Updated!', 'Mokam status has been updated.', 'success');
                        })
                        .catch((error) => {
                            this.$toaster.error('Failed to update status');
                            console.error(error);
                        });
                }
            });
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
                    axios.delete('/api/mokams/' + id)
                        .then((response) => {
                            this.getAllMokams();
                            Swal.fire('Deleted!', 'Mokam has been deleted.', 'success');
                        })
                        .catch((error) => {
                            this.$toaster.error('Failed to delete mokam');
                            console.error(error);
                        });
                }
            });
        },

        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        getSerialNumber(index) {
            return ((this.pagination.current_page - 1) * this.pagination.per_page) + index + 1;
        }
    },
}
</script>

<style scoped>
.error {
    color: #dc3545;
    font-size: 12px;
    margin-top: 4px;
}

.badge {
    font-size: 11px;
    padding: 5px 10px;
}

.btn-sm {
    margin: 0 2px;
}

.card-tools input {
    min-width: 250px;
}
</style>
