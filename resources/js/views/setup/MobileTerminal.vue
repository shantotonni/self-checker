<!-- resources/js/pages/MobileTerminal.vue -->
<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Self Checker', 'Mobile Terminal']"/>

            <!-- Alert if no outlet selected -->
            <div class="alert alert-warning" v-if="!hasSelectedOutlet">
                <i class="fas fa-exclamation-triangle"></i>
                Please select an outlet from the navbar to manage mobile terminals
            </div>

            <div class="row" v-else>
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-mobile-alt mr-2"></i> Mobile Terminal Management
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
                                    <div class="col-md-2">
                                        <input v-model="query" type="text" class="form-control form-control-sm" placeholder="Search...">
                                    </div>
                                    <div class="col-md-2">
                                        <select v-model="filterStatus" class="form-control form-control-sm" @change="fetchTerminals">
                                            <option value="">All Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select v-model="filterSynced" class="form-control form-control-sm" @change="fetchTerminals">
                                            <option value="">All Sync Status</option>
                                            <option value="1">Synced</option>
                                            <option value="0">Not Synced</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select v-model="filterDeviceType" class="form-control form-control-sm" @change="fetchTerminals">
                                            <option value="">All Device Types</option>
                                            <option :value="type" v-for="(type, index) in deviceTypes" :key="index">{{ type }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <button type="button" class="btn btn-success btn-sm" @click="openModal">
                                            <i class="fas fa-plus"></i> Add Terminal
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
                                            <th>Device ID</th>
                                            <th>Device Name</th>
                                            <th>Model</th>
                                            <th>Manufacturer</th>
                                            <th>Serial Number</th>
                                            <th>Android ID</th>
                                            <th>Device Type</th>
                                            <th>Printer Type</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Synced</th>
                                            <th class="text-center" width="100">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(terminal, i) in terminals" :key="terminal.id">
                                            <td class="text-center">{{ (pagination.current_page - 1) * pagination.per_page + (i + 1) }}</td>
                                            <td>
                                                <span class="badge badge-secondary">{{ terminal.device_id }}</span>
                                            </td>
                                            <td class="font-weight-bold">{{ terminal.device_name }}</td>
                                            <td>{{ terminal.model || '-' }}</td>
                                            <td>{{ terminal.manufacturer || '-' }}</td>
                                            <td><code>{{ terminal.serial_number || 'N/A' }}</code></td>
                                            <td><small>{{ terminal.android_id || '-' }}</small></td>
                                            <td>
                                                <span class="badge badge-info" v-if="terminal.device_type">{{ terminal.device_type }}</span>
                                                <span v-else>-</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary" v-if="terminal.printerType">{{ terminal.printerType }}</span>
                                                <span v-else>-</span>
                                            </td>
                                            <td class="text-center">
                                                    <span :class="terminal.status == 1 ? 'badge badge-success' : 'badge badge-danger'">
                                                        {{ terminal.status == 1 ? 'Active' : 'Inactive' }}
                                                    </span>
                                            </td>
                                            <td class="text-center">
                                                <button @click="toggleSync(terminal.id)"
                                                        class="btn btn-sm"
                                                        :class="terminal.synced == 1 ? 'btn-success' : 'btn-warning'"
                                                        title="Toggle Sync">
                                                    <i :class="terminal.synced == 1 ? 'fas fa-check-circle' : 'fas fa-sync-alt'"></i>
                                                    {{ terminal.synced == 1 ? 'Synced' : 'Not Synced' }}
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <button @click="edit(terminal)" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                                <button @click="destroy(terminal.id)" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!terminals.length">
                                            <td colspan="12" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                No mobile terminals found
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="fetchTerminals"
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
        <div class="modal fade" id="terminalModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i :class="editMode ? 'fas fa-edit' : 'fas fa-plus-circle'"></i>
                            {{ editMode ? "Edit" : "Add" }} Mobile Terminal
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <h6 class="text-primary mb-3"><i class="fas fa-info-circle"></i> Basic Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Device ID <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.device_id" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('device_id') }"
                                               placeholder="e.g., DEV-001">
                                        <div class="error" v-if="form.errors.has('device_id')" v-html="form.errors.get('device_id')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Device Name <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.device_name" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('device_name') }"
                                               placeholder="Enter device name">
                                        <div class="error" v-if="form.errors.has('device_name')" v-html="form.errors.get('device_name')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Terminal ID</label>
                                        <input type="text" v-model="form.terminal_id" class="form-control"
                                               placeholder="Terminal ID">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="text-primary mb-3"><i class="fas fa-mobile-alt"></i> Device Details</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Model</label>
                                        <input type="text" v-model="form.model" class="form-control"
                                               placeholder="e.g., Samsung A51">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Manufacturer</label>
                                        <input type="text" v-model="form.manufacturer" class="form-control"
                                               placeholder="e.g., Samsung">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Brand</label>
                                        <input type="text" v-model="form.brand" class="form-control"
                                               placeholder="e.g., Samsung">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Device Type</label>
                                        <input type="text" v-model="form.device_type" class="form-control"
                                               placeholder="e.g., Mobile, Tablet">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Serial Number</label>
                                        <input type="text" v-model="form.serial_number" class="form-control"
                                               placeholder="Serial number">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Android ID</label>
                                        <input type="text" v-model="form.android_id" class="form-control"
                                               placeholder="Android ID">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Android Version</label>
                                        <input type="text" v-model="form.android_version" class="form-control"
                                               placeholder="e.g., 11.0">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="text-primary mb-3"><i class="fas fa-cog"></i> Settings</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Printer Type</label>
                                        <select v-model="form.printerType" class="form-control">
                                            <option value="">Select Printer</option>
                                            <option value="Thermal">Thermal</option>
                                            <option value="Bluetooth">Bluetooth</option>
                                            <option value="USB">USB</option>
                                            <option value="Network">Network</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select v-model="form.status" class="form-control"
                                                :class="{ 'is-invalid': form.errors.has('status') }">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <div class="error" v-if="form.errors.has('status')" v-html="form.errors.get('status')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Synced</label>
                                        <select v-model="form.synced" class="form-control">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
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
document.title = 'Mobile Terminal | Self Checker';

export default {
    name: "MobileTerminal",
    data() {
        return {
            terminals: [],
            selectedOutlet: null,
            hasSelectedOutlet: false,
            deviceTypes: [],
            printerTypes: [],
            pagination: {
                current_page: 1,
                per_page: 15
            },
            form: new Form({
                id: '',
                device_id: '',
                device_name: '',
                model: '',
                manufacturer: '',
                serial_number: '',
                android_id: '',
                android_version: '',
                brand: '',
                device_type: '',
                terminal_id: '',
                status: 1,
                synced: 0,
                printerType: '',
            }),
            query: '',
            filterStatus: '',
            filterSynced: '',
            filterDeviceType: '',
            editMode: false,
            isLoading: false
        }
    },
    watch: {
        query: function(newQ) {
            if (newQ === '') {
                this.fetchTerminals();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        this.checkSelectedOutlet();

        // Listen for outlet changes
        this.$root.$on('outletChanged', (outlet) => {
            this.selectedOutlet = outlet;
            this.hasSelectedOutlet = true;
            this.fetchTerminals();
            this.fetchDeviceTypes();
        });

        if (this.hasSelectedOutlet) {
            this.fetchTerminals();
            this.fetchDeviceTypes();
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

        fetchTerminals() {
            if (!this.hasSelectedOutlet) return;

            this.isLoading = true;
            let url = `/api/mobile-terminals?page=${this.pagination.current_page}`;
            if (this.filterStatus !== '') url += `&status=${this.filterStatus}`;
            if (this.filterSynced !== '') url += `&synced=${this.filterSynced}`;
            if (this.filterDeviceType) url += `&device_type=${this.filterDeviceType}`;

            axios.get(url, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.terminals = res.data.data;
                this.pagination = res.data.meta;
                this.isLoading = false;
            }).catch(err => {
                this.isLoading = false;
                this.$toaster.error(err.response?.data?.message || 'Failed to load terminals');
            });
        },

        fetchDeviceTypes() {
            axios.get('/api/mobile-terminals/device-types', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.deviceTypes = res.data.device_types || [];
            }).catch(err => {});
        },

        searchData() {
            if (!this.hasSelectedOutlet) return;

            axios.get(`/api/mobile-terminals?search=${this.query}&page=${this.pagination.current_page}`, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.terminals = res.data.data;
                this.pagination = res.data.meta;
            }).catch(err => {});
        },

        reload() {
            this.query = '';
            this.filterStatus = '';
            this.filterSynced = '';
            this.filterDeviceType = '';
            this.pagination.current_page = 1;
            this.fetchTerminals();
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
            this.form.status = 1;
            this.form.synced = 0;
            $('#terminalModal').modal('show');
        },

        closeModal() {
            $('#terminalModal').modal('hide');
        },

        store() {
            this.form.busy = true;
            this.form.post('/api/mobile-terminals', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                $('#terminalModal').modal('hide');
                this.fetchTerminals();
                this.$toaster.success('Mobile terminal created successfully');
            }).catch(err => {
                this.form.busy = false;
            });
        },

        edit(terminal) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(terminal);
            $('#terminalModal').modal('show');
        },

        update() {
            this.form.busy = true;
            this.form.put(`/api/mobile-terminals/${this.form.id}`, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                $('#terminalModal').modal('hide');
                this.fetchTerminals();
                this.$toaster.success('Mobile terminal updated successfully');
            }).catch(err => {
                this.form.busy = false;
            });
        },

        destroy(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This terminal will be deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.delete(`/api/mobile-terminals/${id}`, {
                        headers: { 'X-Outlet-ID': this.getOutletId() }
                    }).then(res => {
                        this.fetchTerminals();
                        Swal.fire('Deleted!', 'Terminal has been deleted.', 'success');
                    }).catch(err => {
                        Swal.fire('Error!', 'Failed to delete terminal.', 'error');
                    });
                }
            });
        },

        toggleSync(id) {
            axios.post(`/api/mobile-terminals/${id}/toggle-sync`, {}, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.fetchTerminals();
                this.$toaster.success('Sync status updated');
            }).catch(err => {
                this.$toaster.error('Failed to update sync status');
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
