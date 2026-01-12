<!-- resources/js/pages/AppVersionInfo.vue -->
<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Self Checker', 'App Version Info']"/>

            <!-- Alert if no outlet selected -->
            <div class="alert alert-warning" v-if="!hasSelectedOutlet">
                <i class="fas fa-exclamation-triangle"></i>
                Please select an outlet from the navbar to manage app versions
            </div>

            <div class="row" v-else>
                <!-- Statistics Cards -->
                <div class="col-xl-4 col-md-6" v-if="stats">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-mobile-alt fa-3x"></i>
                                </div>
                                <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Total Apps</h5>
                                <h4 class="font-weight-medium font-size-24">{{ stats.total_apps || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6" v-if="stats">
                    <div class="card mini-stat bg-success text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-check-circle fa-3x"></i>
                                </div>
                                <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Active Apps</h5>
                                <h4 class="font-weight-medium font-size-24">{{ stats.active_apps || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6" v-if="stats">
                    <div class="card mini-stat bg-danger text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-times-circle fa-3x"></i>
                                </div>
                                <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Inactive Apps</h5>
                                <h4 class="font-weight-medium font-size-24">{{ stats.inactive_apps || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Table -->
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-code-branch mr-2"></i> App Version Management
                                </h5>
                                <small v-if="selectedOutlet">
                                    <i class="fas fa-store"></i> {{ selectedOutlet.OutletName }}
                                </small>
                            </div>
                        </div>

                        <div class="datatable" v-if="!isLoading">
                            <div class="card-body">
                                <!-- Filters & Actions -->
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <input v-model="query" type="text" class="form-control form-control-sm" placeholder="Search...">
                                    </div>
                                    <div class="col-md-2">
                                        <select v-model="filterStatus" class="form-control form-control-sm" @change="fetchAppVersions">
                                            <option value="">All Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select v-model="filterPackage" class="form-control form-control-sm" @change="fetchAppVersions">
                                            <option value="">All Packages</option>
                                            <option :value="pkg" v-for="(pkg, index) in packages" :key="index">{{ pkg }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-2 text-right">
                                        <button type="button" class="btn btn-success btn-sm" @click="openModal">
                                            <i class="fas fa-plus"></i> Add App
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                            <i class="fas fa-sync"></i> Reload
                                        </button>
                                    </div>
                                </div>

                                <!-- Data Table -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm small">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center" width="40">SN</th>
                                            <th>App Name</th>
                                            <th>Package Name</th>
                                            <th class="text-center">Version Name</th>
                                            <th class="text-center">Version Code</th>
                                            <th class="text-center">Min Version</th>
                                            <th class="text-center">Current Version</th>
                                            <th class="text-center">Download Link</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center" width="100">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(app, i) in appVersions" :key="app.ID">
                                            <td class="text-center">{{ (pagination.current_page - 1) * pagination.per_page + (i + 1) }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-mobile-alt mr-2 text-primary"></i>
                                                    <strong>{{ app.AppName }}</strong>
                                                </div>
                                            </td>
                                            <td><code>{{ app.PackageName }}</code></td>
                                            <td class="text-center">
                                                <span class="badge badge-info">{{ app.VersionName }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-secondary">{{ app.VersionCode }}</span>
                                            </td>
                                            <td class="text-center">{{ app.MinVersion || '-' }}</td>
                                            <td class="text-center">{{ app.CurrentVersion || '-' }}</td>
                                            <td class="text-center">
                                                <a v-if="app.DownloadLink" :href="app.DownloadLink" target="_blank"
                                                   class="btn btn-sm btn-outline-primary" title="Download">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                                <span v-else class="text-muted">-</span>
                                            </td>
                                            <td class="text-center">
                                                <button @click="toggleStatus(app.ID)"
                                                        class="btn btn-sm"
                                                        :class="app.ActiveStatus == 1 ? 'btn-success' : 'btn-danger'">
                                                    <i :class="app.ActiveStatus == 1 ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                                    {{ app.ActiveStatus == 1 ? 'Active' : 'Inactive' }}
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <button @click="edit(app)" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                                <button @click="destroy(app.ID)" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!appVersions.length">
                                            <td colspan="10" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                No app version information found
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="fetchAppVersions"
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
        <div class="modal fade" id="appVersionModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i :class="editMode ? 'fas fa-edit' : 'fas fa-plus-circle'"></i>
                            {{ editMode ? "Edit" : "Add" }} App Version
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <!-- App Details -->
                            <h6 class="text-primary mb-3"><i class="fas fa-mobile-alt"></i> App Details</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>App Name <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.AppName" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('AppName') }"
                                               placeholder="e.g., Self Checker App">
                                        <div class="error" v-if="form.errors.has('AppName')" v-html="form.errors.get('AppName')" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Package Name <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.PackageName" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('PackageName') }"
                                               placeholder="e.g., com.example.app">
                                        <div class="error" v-if="form.errors.has('PackageName')" v-html="form.errors.get('PackageName')" />
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="text-primary mb-3"><i class="fas fa-code-branch"></i> Version Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Version Name <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.VersionName" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('VersionName') }"
                                               placeholder="e.g., 1.0.0">
                                        <div class="error" v-if="form.errors.has('VersionName')" v-html="form.errors.get('VersionName')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Version Code <span class="text-danger">*</span></label>
                                        <input type="number" v-model="form.VersionCode" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('VersionCode') }"
                                               placeholder="e.g., 1">
                                        <div class="error" v-if="form.errors.has('VersionCode')" v-html="form.errors.get('VersionCode')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select v-model="form.ActiveStatus" class="form-control"
                                                :class="{ 'is-invalid': form.errors.has('ActiveStatus') }">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <div class="error" v-if="form.errors.has('ActiveStatus')" v-html="form.errors.get('ActiveStatus')" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Minimum Version</label>
                                        <input type="number" v-model="form.MinVersion" class="form-control"
                                               placeholder="Minimum supported version code">
                                        <small class="text-muted">Users below this version must update</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Current Version</label>
                                        <input type="number" v-model="form.CurrentVersion" class="form-control"
                                               placeholder="Current version code">
                                        <small class="text-muted">Latest available version</small>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="text-primary mb-3"><i class="fas fa-link"></i> Download Link</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Download URL</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-download"></i></span>
                                            </div>
                                            <input type="text" v-model="form.DownloadLink" class="form-control"
                                                   placeholder="https://example.com/app.apk">
                                        </div>
                                        <small class="text-muted">Direct download link for the APK file</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">
                                <i class="fas fa-times"></i> Close
                            </button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                <i :class="form.busy ? 'fas fa-spinner fa-spin' : 'fas fa-save'"></i>
                                {{ editMode ? "Update" : "Create" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.title = 'App Version Info | Self Checker';

export default {
    name: "AppVersionInfo",
    data() {
        return {
            appVersions: [],
            selectedOutlet: null,
            hasSelectedOutlet: false,
            packages: [],
            stats: null,
            pagination: {
                current_page: 1,
                per_page: 15
            },
            form: new Form({
                ID: '',
                AppName: '',
                PackageName: '',
                VersionName: '',
                VersionCode: '',
                MinVersion: '',
                CurrentVersion: '',
                DownloadLink: '',
                ActiveStatus: 1,
            }),
            query: '',
            filterStatus: '',
            filterPackage: '',
            editMode: false,
            isLoading: false
        }
    },
    watch: {
        query: function(newQ) {
            if (newQ === '') {
                this.fetchAppVersions();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        this.checkSelectedOutlet();

        this.$root.$on('outletChanged', (outlet) => {
            this.selectedOutlet = outlet;
            this.hasSelectedOutlet = true;
            this.fetchAppVersions();
            this.fetchPackages();
            this.fetchStats();
        });

        if (this.hasSelectedOutlet) {
            this.fetchAppVersions();
            this.fetchPackages();
            this.fetchStats();
        }
    },
    methods: {
        checkSelectedOutlet() {
            const savedOutlet = localStorage.getItem('selectedOutlet');
            if (savedOutlet) {
                this.selectedOutlet = JSON.parse(savedOutlet);
                this.hasSelectedOutlet = true;
            }
        },

        getOutletId() {
            return localStorage.getItem('selected_outlet_id');
        },

        fetchAppVersions() {
            if (!this.hasSelectedOutlet) return;

            this.isLoading = true;
            let url = `/api/app-versions?page=${this.pagination.current_page}`;
            if (this.filterStatus !== '') url += `&active_status=${this.filterStatus}`;
            if (this.filterPackage) url += `&package_name=${this.filterPackage}`;

            axios.get(url, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.appVersions = res.data.data;
                this.pagination = res.data.meta;
                this.isLoading = false;
            }).catch(err => {
                this.isLoading = false;
                this.$toaster.error(err.response?.data?.message || 'Failed to load app versions');
            });
        },

        fetchPackages() {
            axios.get('/api/app-versions/packages', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.packages = res.data.packages || [];
            }).catch(err => {});
        },

        fetchStats() {
            axios.get('/api/app-versions/stats', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.stats = res.data.stats;
            }).catch(err => {});
        },

        searchData() {
            if (!this.hasSelectedOutlet) return;

            axios.get(`/api/app-versions?search=${this.query}&page=${this.pagination.current_page}`, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.appVersions = res.data.data;
                this.pagination = res.data.meta;
            }).catch(err => {});
        },

        reload() {
            this.query = '';
            this.filterStatus = '';
            this.filterPackage = '';
            this.pagination.current_page = 1;
            this.fetchAppVersions();
            this.fetchStats();
            this.$toaster.success('Data refreshed');
        },

        openModal() {
            if (!this.hasSelectedOutlet) {
                this.$toaster.warning('Please select an outlet first');
                return;
            }

            this.editMode = false;
            this.form.reset();
            this.form.clear();
            this.form.ActiveStatus = 1;
            $('#appVersionModal').modal('show');
        },

        closeModal() {
            $('#appVersionModal').modal('hide');
        },

        store() {
            this.form.busy = true;
            this.form.post('/api/app-versions', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                $('#appVersionModal').modal('hide');
                this.fetchAppVersions();
                this.fetchStats();
                this.$toaster.success('App version created successfully');
            }).catch(err => {
                this.form.busy = false;
            });
        },

        edit(app) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(app);
            $('#appVersionModal').modal('show');
        },

        update() {
            this.form.busy = true;
            this.form.put(`/api/app-versions/${this.form.ID}`, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                $('#appVersionModal').modal('hide');
                this.fetchAppVersions();
                this.fetchStats();
                this.$toaster.success('App version updated successfully');
            }).catch(err => {
                this.form.busy = false;
            });
        },

        destroy(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This app version will be deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.delete(`/api/app-versions/${id}`, {
                        headers: { 'X-Outlet-ID': this.getOutletId() }
                    }).then(res => {
                        this.fetchAppVersions();
                        this.fetchStats();
                        Swal.fire('Deleted!', 'App version has been deleted.', 'success');
                    }).catch(err => {
                        Swal.fire('Error!', 'Failed to delete app version.', 'error');
                    });
                }
            });
        },

        toggleStatus(id) {
            axios.post(`/api/app-versions/${id}/toggle-status`, {}, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.fetchAppVersions();
                this.fetchStats();
                this.$toaster.success('Status updated successfully');
            }).catch(err => {
                this.$toaster.error('Failed to update status');
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
.mini-stat {
    overflow: hidden;
}
.mini-stat-img i {
    opacity: 0.3;
}
</style>
