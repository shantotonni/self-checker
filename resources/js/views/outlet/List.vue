<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Outlet Management']"/>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-store-alt mr-2"></i> Outlet List</h5>
                        </div>
                        <div class="datatable" v-if="!isLoading">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input v-model="query" type="text" class="form-control" placeholder="Search...">
                                            </div>
                                            <div class="col-md-2">
                                                <select v-model="filterStatus" class="form-control" @change="filterByStatus">
                                                    <option value="">All Status</option>
                                                    <option value="Active">Active</option>
                                                    <option value="Inactive">Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select v-model="filterCity" class="form-control" @change="filterByCity">
                                                    <option value="">All Cities</option>
                                                    <option :value="city" v-for="(city, index) in cities" :key="index">{{ city }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-success btn-sm" @click="openModal">
                                            <i class="fas fa-plus"></i> Add Outlet
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                            <i class="fas fa-sync"></i> Reload
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center" width="50">SN</th>
                                            <th class="text-left">Outlet Code</th>
                                            <th class="text-left">Outlet Name</th>
                                            <th class="text-left">IP Address</th>
                                            <th class="text-left">City</th>
                                            <th class="text-left">District</th>
                                            <th class="text-center">Opening Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">DB Config</th>
                                            <th class="text-center" width="120">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(outlet, i) in outlets" :key="outlet.OutletID" v-if="outlets.length">
                                            <td class="text-center">{{ (pagination.current_page - 1) * pagination.per_page + (i + 1) }}</td>
                                            <td class="text-left">
                                                <span class="badge badge-secondary">{{ outlet.OutletCode }}</span>
                                            </td>
                                            <td class="text-left font-weight-bold">{{ outlet.OutletName }}</td>
                                            <td class="text-left">
                                                <code>{{ outlet.IPAddress || 'N/A' }}</code>
                                            </td>
                                            <td class="text-left">{{ outlet.City || '-' }}</td>
                                            <td class="text-left">{{ outlet.District || '-' }}</td>
                                            <td class="text-center">
                                                <small>{{ outlet.OpeningDate | formatDate }}</small>
                                            </td>
                                            <td class="text-center">
                                                    <span :class="outlet.Status === 'Active' ? 'badge badge-success' : 'badge badge-danger'">
                                                        {{ outlet.Status }}
                                                    </span>
                                            </td>
                                            <td class="text-center">
                                                    <span v-if="outlet.DatabaseName" class="badge badge-info" title="Database configured">
                                                        <i class="fas fa-database"></i> {{ outlet.DatabaseName }}
                                                    </span>
                                                <span v-else class="badge badge-warning">
                                                        <i class="fas fa-exclamation-triangle"></i> Not Set
                                                    </span>
                                            </td>
                                            <td class="text-center">
                                                <button @click="edit(outlet)" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                                <button @click="destroy(outlet.OutletID)" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!outlets.length">
                                            <td colspan="10" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                No outlets found
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="fetchOutlets"
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

        <!-- Add/Edit Modal -->
        <div class="modal fade" id="outletModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i :class="editMode ? 'fas fa-edit' : 'fas fa-plus-circle'"></i>
                            {{ editMode ? "Edit" : "Add" }} Outlet
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <!-- Basic Information -->
                            <h6 class="text-primary mb-3"><i class="fas fa-info-circle"></i> Basic Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Outlet Code <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.OutletCode" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('OutletCode') }"
                                               placeholder="e.g., OUT-001">
                                        <div class="error" v-if="form.errors.has('OutletCode')" v-html="form.errors.get('OutletCode')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Outlet Name <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.OutletName" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('OutletName') }"
                                               placeholder="Enter outlet name">
                                        <div class="error" v-if="form.errors.has('OutletName')" v-html="form.errors.get('OutletName')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>IP Address <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.IPAddress" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('IPAddress') }"
                                               placeholder="e.g., 192.168.1.100">
                                        <div class="error" v-if="form.errors.has('IPAddress')" v-html="form.errors.get('IPAddress')" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" v-model="form.City" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('City') }"
                                               placeholder="e.g., Dhaka">
                                        <div class="error" v-if="form.errors.has('City')" v-html="form.errors.get('City')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>District</label>
                                        <input type="text" v-model="form.District" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('District') }"
                                               placeholder="e.g., Dhaka">
                                        <div class="error" v-if="form.errors.has('District')" v-html="form.errors.get('District')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select v-model="form.Status" class="form-control"
                                                :class="{ 'is-invalid': form.errors.has('Status') }">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                        <div class="error" v-if="form.errors.has('Status')" v-html="form.errors.get('Status')" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Opening Date <span class="text-danger">*</span></label>
                                        <input type="date" v-model="form.OpeningDate" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('OpeningDate') }">
                                        <div class="error" v-if="form.errors.has('OpeningDate')" v-html="form.errors.get('OpeningDate')" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Closing Date</label>
                                        <input type="date" v-model="form.ClosingDate" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('ClosingDate') }">
                                        <div class="error" v-if="form.errors.has('ClosingDate')" v-html="form.errors.get('ClosingDate')" />
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Database Configuration -->
                            <h6 class="text-primary mb-3"><i class="fas fa-database"></i> Database Configuration</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Database Name <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.DatabaseName" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('DatabaseName') }"
                                               placeholder="e.g., pos_db">
                                        <div class="error" v-if="form.errors.has('DatabaseName')" v-html="form.errors.get('DatabaseName')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Database User <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.DatabaseUser" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('DatabaseUser') }"
                                               placeholder="e.g., sa">
                                        <div class="error" v-if="form.errors.has('DatabaseUser')" v-html="form.errors.get('DatabaseUser')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Database Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input :type="showPassword ? 'text' : 'password'"
                                                   v-model="form.DatabasePassword" class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('DatabasePassword') }"
                                                   placeholder="••••••••">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" @click="showPassword = !showPassword">
                                                    <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="error" v-if="form.errors.has('DatabasePassword')" v-html="form.errors.get('DatabasePassword')" />
                                        <small class="text-muted" v-if="editMode">Leave empty to keep current password</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Test Connection -->
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-info btn-sm" @click="testConnection" :disabled="testing">
                                        <i :class="testing ? 'fas fa-spinner fa-spin' : 'fas fa-plug'"></i>
                                        Test Connection
                                    </button>
                                    <span class="ml-2" v-if="testResult.message">
                                        <span :class="testResult.success ? 'text-success' : 'text-danger'">
                                            <i :class="testResult.success ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                            {{ testResult.message }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">
                                <i class="fas fa-times"></i> Close
                            </button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                <i :class="form.busy ? 'fas fa-spinner fa-spin' : 'fas fa-save'"></i>
                                {{ editMode ? "Update" : "Create" }} Outlet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.title = 'Outlet Management | Self Checker';

export default {
    name: "OutletList",
    data() {
        return {
            outlets: [],
            cities: [],
            pagination: {
                current_page: 1,
                per_page: 15
            },
            form: new Form({
                OutletID: '',
                OutletCode: '',
                OutletName: '',
                IPAddress: '',
                City: '',
                District: '',
                OpeningDate: '',
                ClosingDate: '',
                Status: 'Active',
                DatabaseName: '',
                DatabaseUser: '',
                DatabasePassword: '',
            }),
            query: '',
            filterStatus: '',
            filterCity: '',
            editMode: false,
            isLoading: false,
            showPassword: false,
            testing: false,
            testResult: { success: false, message: '' }
        }
    },
    filters: {
        formatDate(value) {
            if (!value) return '-';
            const date = new Date(value);
            return date.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }
    },
    watch: {
        query: function(newQ) {
            if (newQ === '') {
                this.fetchOutlets();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        this.fetchOutlets();
    },
    methods: {
        fetchOutlets() {
            this.isLoading = true;
            let url = '/api/outlets?page=' + this.pagination.current_page;
            if (this.filterStatus) url += '&status=' + this.filterStatus;
            if (this.filterCity) url += '&city=' + this.filterCity;

            axios.get(url).then(res => {
                this.outlets = res.data.data;
                this.pagination = res.data.meta;
                this.isLoading = false;
            }).catch(err => {
                this.isLoading = false;
                this.$toaster.error('Failed to load outlets');
            });
        },

        fetchCities() {
            axios.get('/api/outlets/cities').then(res => {
                this.cities = res.data.cities;
            }).catch(err => {});
        },

        searchData() {
            axios.get('/api/outlets/search/' + this.query + '?page=' + this.pagination.current_page)
                .then(res => {
                    this.outlets = res.data.data;
                    this.pagination = res.data.meta;
                }).catch(err => {});
        },

        filterByStatus() {
            this.pagination.current_page = 1;
            this.fetchOutlets();
        },

        filterByCity() {
            this.pagination.current_page = 1;
            this.fetchOutlets();
        },

        reload() {
            this.query = '';
            this.filterStatus = '';
            this.filterCity = '';
            this.pagination.current_page = 1;
            this.fetchOutlets();
            this.$toaster.success('Data refreshed');
        },

        openModal() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            this.form.Status = 'Active';
            this.showPassword = false;
            this.testResult = { success: false, message: '' };
            $('#outletModal').modal('show');
        },

        closeModal() {
            $('#outletModal').modal('hide');
        },

        store() {
            this.form.busy = true;
            this.form.post('/api/outlets').then(res => {
                $('#outletModal').modal('hide');
                this.fetchOutlets();
                this.$toaster.success('Outlet created successfully');
            }).catch(err => {
                this.form.busy = false;
            });
        },

        edit(outlet) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(outlet);
            this.form.DatabasePassword = ''; // Don't show password
            this.showPassword = false;
            this.testResult = { success: false, message: '' };
            $('#outletModal').modal('show');
        },

        update() {
            this.form.busy = true;
            this.form.put('/api/outlets/' + this.form.OutletID).then(res => {
                $('#outletModal').modal('hide');
                this.fetchOutlets();
                this.$toaster.success('Outlet updated successfully');
            }).catch(err => {
                this.form.busy = false;
            });
        },

        destroy(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This outlet will be deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.delete('/api/outlets/' + id).then(res => {
                        this.fetchOutlets();
                        Swal.fire('Deleted!', 'Outlet has been deleted.', 'success');
                    }).catch(err => {
                        Swal.fire('Error!', 'Failed to delete outlet.', 'error');
                    });
                }
            });
        },

        testConnection() {
            if (!this.form.IPAddress || !this.form.DatabaseName || !this.form.DatabaseUser) {
                this.$toaster.warning('Please fill IP, Database Name and User');
                return;
            }

            this.testing = true;
            this.testResult = { success: false, message: '' };

            axios.post('/api/outlets/test-connection', {
                IPAddress: this.form.IPAddress,
                DatabaseName: this.form.DatabaseName,
                DatabaseUser: this.form.DatabaseUser,
                DatabasePassword: this.form.DatabasePassword
            }).then(res => {
                this.testing = false;
                this.testResult = { success: res.data.success, message: res.data.message };
            }).catch(err => {
                this.testing = false;
                this.testResult = { success: false, message: err.response?.data?.message || 'Connection failed' };
            });
        }
    }
}
</script>

<style scoped>
.error {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
</style>
