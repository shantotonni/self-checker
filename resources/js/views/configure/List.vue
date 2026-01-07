<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Outlet Configuration']"/>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-cogs mr-2"></i> Outlet Configuration Panel</h5>
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
                                                <select v-model="filterOutlet" class="form-control" @change="filterByOutlet">
                                                    <option value="">All Outlets</option>
                                                    <option :value="outlet.OutletID" v-for="outlet in outlets" :key="outlet.OutletID">
                                                        {{ outlet.OutletName }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select v-model="filterStatus" class="form-control" @change="filterByStatus">
                                                    <option value="">All Status</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Executed">Executed</option>
                                                    <option value="Failed">Failed</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-success btn-sm" @click="openConfigModal">
                                            <i class="fas fa-plus"></i> New Configuration
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" @click="openBulkModal">
                                            <i class="fas fa-layer-group"></i> Bulk Execute
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                            <i class="fas fa-sync"></i> Reload
                                        </button>
                                    </div>
                                </div>

                                <!-- Configuration History Table -->
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center" width="50">SN</th>
                                            <th class="text-left">Outlet</th>
                                            <th class="text-left">IP Address</th>
                                            <th class="text-left">Config Type</th>
                                            <th class="text-left">Description</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Executed At</th>
                                            <th class="text-center" width="150">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(config, i) in configurations" :key="config.ConfigID" v-if="configurations.length">
                                            <td class="text-center">{{ (pagination.current_page - 1) * pagination.per_page + (i + 1) }}</td>
                                            <td class="text-left">
                                                <strong>{{ config.OutletName }}</strong><br>
                                                <small class="text-muted">{{ config.OutletCode }}</small>
                                            </td>
                                            <td class="text-left"><code>{{ config.IPAddress }}</code></td>
                                            <td class="text-left">
                                                <span class="badge badge-info">{{ config.ConfigType }}</span>
                                            </td>
                                            <td class="text-left">{{ config.Description | truncate(50) }}</td>
                                            <td class="text-center">
                                                    <span :class="getStatusBadge(config.ExecutionStatus)">
                                                        {{ config.ExecutionStatus }}
                                                    </span>
                                            </td>
                                            <td class="text-center">
                                                <small v-if="config.ExecutedAt">{{ config.ExecutedAt | formatDateTime }}</small>
                                                <span v-else class="text-muted">-</span>
                                            </td>
                                            <td class="text-center">
                                                <button @click="viewConfig(config)" class="btn btn-info btn-xs mr-1" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button @click="executeConfig(config)" class="btn btn-success btn-xs mr-1" title="Execute"
                                                        :disabled="config.ExecutionStatus === 'Executed'">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button @click="editConfig(config)" class="btn btn-warning btn-xs mr-1" title="Edit"
                                                        :disabled="config.ExecutionStatus === 'Executed'">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button @click="deleteConfig(config.ConfigID)" class="btn btn-danger btn-xs" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!configurations.length">
                                            <td colspan="8" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                No configurations found
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="fetchConfigurations"
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

        <!-- Add/Edit Configuration Modal -->
        <div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i :class="editMode ? 'fas fa-edit' : 'fas fa-plus-circle'"></i>
                            {{ editMode ? 'Edit' : 'New' }} Outlet Configuration
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? updateConfig() : storeConfig()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <!-- Outlet Selection -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span class="text-danger">*</span> Select Outlet</label>
                                        <select name="OutletID" v-model="form.OutletID" class="form-control" :class="{ 'is-invalid': form.errors.has('OutletID') }"
                                                @change="onOutletChange">
                                            <option value="">-- Select Outlet --</option>
                                            <option :value="outlet.OutletID" v-for="outlet in outlets" :key="outlet.OutletID">
                                                {{ outlet.OutletName }} ({{ outlet.OutletCode }})
                                            </option>
                                        </select>
                                        <div class="error" v-if="form.errors.has('OutletID')" v-html="form.errors.get('OutletID')" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IP Address</label>
                                        <input type="text" v-model="selectedOutletIP" class="form-control" readonly
                                               placeholder="Auto-filled from outlet">
                                        <small class="text-muted">This IP will be used for database connection</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Outlet Info Card -->
                            <div class="card bg-light mb-3" v-if="selectedOutlet">
                                <div class="card-body py-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-muted">Outlet:</small><br>
                                            <strong>{{ selectedOutlet.OutletName }}</strong>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Location:</small><br>
                                            <strong>{{ selectedOutlet.Area }}, {{ selectedOutlet.City }}</strong>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Status:</small><br>
                                            <span :class="selectedOutlet.Status === 'Active' ? 'text-success' : 'text-danger'">
                                                <i :class="selectedOutlet.Status === 'Active' ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                                {{ selectedOutlet.Status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Configuration Type -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span class="text-danger">*</span> Configuration Type</label>
                                        <select name="ConfigType" v-model="form.ConfigType" class="form-control"
                                                :class="{ 'is-invalid': form.errors.has('ConfigType') }">
                                            <option value="">-- Select Type --</option>
                                            <option value="SQL_SCRIPT">SQL Script</option>
                                            <option value="DATA_UPDATE">Data Update</option>
                                            <option value="SETTINGS_CHANGE">Settings Change</option>
                                            <option value="PRICE_UPDATE">Price Update</option>
                                            <option value="MENU_SYNC">Menu Sync</option>
                                            <option value="USER_PERMISSION">User Permission</option>
                                            <option value="SYSTEM_CONFIG">System Config</option>
                                        </select>
                                        <div class="error" v-if="form.errors.has('ConfigType')" v-html="form.errors.get('ConfigType')" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Priority</label>
                                        <select name="Priority" v-model="form.Priority" class="form-control">
                                            <option value="Low">Low</option>
                                            <option value="Medium">Medium</option>
                                            <option value="High">High</option>
                                            <option value="Critical">Critical</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Description</label>
                                <input type="text" name="Description" v-model="form.Description" class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('Description') }"
                                       placeholder="Brief description of this configuration">
                                <div class="error" v-if="form.errors.has('Description')" v-html="form.errors.get('Description')" />
                            </div>

                            <!-- Database Credentials (Optional Override) -->
                            <div class="card mb-3">
                                <div class="card-header py-2 bg-light">
                                    <div class="d-flex align-items-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="useCustomCredentials"
                                                   v-model="useCustomCredentials">
                                            <label class="custom-control-label" for="useCustomCredentials">
                                                <strong>Use Custom Database Credentials</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" v-if="useCustomCredentials">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group mb-0">
                                                <label>Database Name</label>
                                                <input type="text" v-model="form.DatabaseName" class="form-control"
                                                       placeholder="database_name">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-0">
                                                <label>Port</label>
                                                <input type="text" v-model="form.DatabasePort" class="form-control"
                                                       placeholder="1433">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-0">
                                                <label>Username</label>
                                                <input type="text" v-model="form.DatabaseUser" class="form-control"
                                                       placeholder="sa">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-0">
                                                <label>Password</label>
                                                <input type="password" v-model="form.DatabasePassword" class="form-control"
                                                       placeholder="********">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SQL Script / Query -->
                            <div class="form-group">
                                <label><span class="text-danger">*</span> SQL Script / Query</label>
                                <textarea name="SqlScript" v-model="form.SqlScript" class="form-control font-monospace"
                                          rows="8" :class="{ 'is-invalid': form.errors.has('SqlScript') }"
                                          placeholder="Enter SQL script to execute on outlet database...
Example:
UPDATE Products SET Price = 100 WHERE ProductID = 1;
UPDATE Settings SET Value = 'true' WHERE Key = 'EnableFeature';"></textarea>
                                <div class="error" v-if="form.errors.has('SqlScript')" v-html="form.errors.get('SqlScript')" />
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    This script will be executed on the selected outlet's database
                                </small>
                            </div>

                            <!-- Parameters (JSON) -->
                            <div class="form-group">
                                <label>Parameters (JSON) <small class="text-muted">- Optional</small></label>
                                <textarea name="Parameters" v-model="form.Parameters" class="form-control font-monospace"
                                          rows="3" placeholder='{"key": "value", "productId": 123}'></textarea>
                                <small class="text-muted">Additional parameters in JSON format</small>
                            </div>

                            <!-- Schedule (Optional) -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Schedule Execution</label>
                                        <input type="datetime-local" name="ScheduledAt" v-model="form.ScheduledAt"
                                               class="form-control">
                                        <small class="text-muted">Leave empty to execute manually</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="Remarks" v-model="form.Remarks" class="form-control"
                                               placeholder="Any additional notes...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">
                                <i class="fas fa-times"></i> Close
                            </button>
                            <button type="button" class="btn btn-info" @click="testConnection" :disabled="!form.OutletID || testingConnection">
                                <i :class="testingConnection ? 'fas fa-spinner fa-spin' : 'fas fa-plug'"></i>
                                Test Connection
                            </button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                <i :class="form.busy ? 'fas fa-spinner fa-spin' : 'fas fa-save'"></i>
                                {{ editMode ? 'Update' : 'Save' }} Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Configuration Modal -->
        <div class="modal fade" id="viewConfigModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="fas fa-eye"></i> Configuration Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body" v-if="viewingConfig">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">Outlet:</th>
                                        <td><strong>{{ viewingConfig.OutletName }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>IP Address:</th>
                                        <td><code>{{ viewingConfig.IPAddress }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Config Type:</th>
                                        <td><span class="badge badge-info">{{ viewingConfig.ConfigType }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Priority:</th>
                                        <td><span :class="getPriorityBadge(viewingConfig.Priority)">{{ viewingConfig.Priority }}</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">Status:</th>
                                        <td><span :class="getStatusBadge(viewingConfig.ExecutionStatus)">{{ viewingConfig.ExecutionStatus }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Created At:</th>
                                        <td>{{ viewingConfig.CreatedAt | formatDateTime }}</td>
                                    </tr>
                                    <tr>
                                        <th>Executed At:</th>
                                        <td>{{ viewingConfig.ExecutedAt ? (viewingConfig.ExecutedAt | formatDateTime) : 'Not executed' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created By:</th>
                                        <td>{{ viewingConfig.CreatedBy }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><strong>Description:</strong></label>
                            <p class="border rounded p-2 bg-light">{{ viewingConfig.Description }}</p>
                        </div>

                        <div class="form-group">
                            <label><strong>SQL Script:</strong></label>
                            <pre class="border rounded p-2 bg-dark text-light" style="max-height: 200px; overflow-y: auto;">{{ viewingConfig.SqlScript }}</pre>
                        </div>

                        <div class="form-group" v-if="viewingConfig.ExecutionResult">
                            <label><strong>Execution Result:</strong></label>
                            <pre class="border rounded p-2" :class="viewingConfig.ExecutionStatus === 'Failed' ? 'bg-danger text-white' : 'bg-success text-white'"
                                 style="max-height: 150px; overflow-y: auto;">{{ viewingConfig.ExecutionResult }}</pre>
                        </div>

                        <div class="form-group" v-if="viewingConfig.Remarks">
                            <label><strong>Remarks:</strong></label>
                            <p class="border rounded p-2 bg-light">{{ viewingConfig.Remarks }}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" @click="executeFromView"
                                v-if="viewingConfig && viewingConfig.ExecutionStatus !== 'Executed'">
                            <i class="fas fa-play"></i> Execute Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Execute Modal -->
        <div class="modal fade" id="bulkExecuteModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title"><i class="fas fa-layer-group"></i> Bulk Execute Configuration</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <form @submit.prevent="executeBulk">
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Warning:</strong> This will execute the same script on multiple outlets. Please review carefully.
                            </div>

                            <!-- Select Multiple Outlets -->
                            <div class="form-group">
                                <label><span class="text-danger">*</span> Select Outlets</label>
                                <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="selectAllOutlets"
                                               v-model="selectAllOutlets" @change="toggleSelectAll">
                                        <label class="custom-control-label font-weight-bold" for="selectAllOutlets">
                                            Select All Active Outlets
                                        </label>
                                    </div>
                                    <hr class="my-2">
                                    <div class="custom-control custom-checkbox" v-for="outlet in activeOutlets" :key="outlet.OutletID">
                                        <input type="checkbox" class="custom-control-input" :id="'outlet_' + outlet.OutletID"
                                               :value="outlet.OutletID" v-model="bulkForm.selectedOutlets">
                                        <label class="custom-control-label" :for="'outlet_' + outlet.OutletID">
                                            {{ outlet.OutletName }} <code class="ml-2">{{ outlet.IPAddress }}</code>
                                        </label>
                                    </div>
                                </div>
                                <small class="text-muted">Selected: {{ bulkForm.selectedOutlets.length }} outlets</small>
                            </div>

                            <!-- Config Type & Description -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span class="text-danger">*</span> Configuration Type</label>
                                        <select v-model="bulkForm.ConfigType" class="form-control" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="SQL_SCRIPT">SQL Script</option>
                                            <option value="DATA_UPDATE">Data Update</option>
                                            <option value="PRICE_UPDATE">Price Update</option>
                                            <option value="MENU_SYNC">Menu Sync</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span class="text-danger">*</span> Description</label>
                                        <input type="text" v-model="bulkForm.Description" class="form-control"
                                               placeholder="Bulk configuration description" required>
                                    </div>
                                </div>
                            </div>

                            <!-- SQL Script -->
                            <div class="form-group">
                                <label><span class="text-danger">*</span> SQL Script</label>
                                <textarea v-model="bulkForm.SqlScript" class="form-control font-monospace" rows="6"
                                          placeholder="Enter SQL script..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning" :disabled="bulkExecuting || !bulkForm.selectedOutlets.length">
                                <i :class="bulkExecuting ? 'fas fa-spinner fa-spin' : 'fas fa-play'"></i>
                                Execute on {{ bulkForm.selectedOutlets.length }} Outlets
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.title = 'Outlet Configuration | System';

export default {
    name: "OutletConfiguration",
    data() {
        return {
            configurations: [],
            outlets: [],
            activeOutlets: [],
            selectedOutlet: null,
            selectedOutletIP: '',
            viewingConfig: null,
            pagination: {
                current_page: 1,
                per_page: 15
            },
            form: new Form({
                ConfigID: '',
                OutletID: '',
                ConfigType: '',
                Description: '',
                SqlScript: '',
                Parameters: '',
                Priority: 'Medium',
                ScheduledAt: '',
                Remarks: '',
                DatabaseName: '',
                DatabasePort: '1433',
                DatabaseUser: '',
                DatabasePassword: '',
            }),
            bulkForm: {
                selectedOutlets: [],
                ConfigType: '',
                Description: '',
                SqlScript: '',
            },
            query: '',
            filterOutlet: '',
            filterStatus: '',
            editMode: false,
            isLoading: false,
            testingConnection: false,
            bulkExecuting: false,
            useCustomCredentials: false,
            selectAllOutlets: false,
        }
    },
    filters: {
        formatDateTime(value) {
            if (!value) return '';
            const date = new Date(value);
            return date.toLocaleString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },
        truncate(value, length) {
            if (!value) return '';
            if (value.length <= length) return value;
            return value.substring(0, length) + '...';
        }
    },
    watch: {
        query: function(newQ) {
            if (newQ === '') {
                this.fetchConfigurations();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        this.fetchConfigurations();
        this.fetchOutlets();
    },
    methods: {
        getStatusBadge(status) {
            const badges = {
                'Pending': 'badge badge-warning',
                'Executed': 'badge badge-success',
                'Failed': 'badge badge-danger',
                'Scheduled': 'badge badge-info'
            };
            return badges[status] || 'badge badge-secondary';
        },

        getPriorityBadge(priority) {
            const badges = {
                'Low': 'badge badge-secondary',
                'Medium': 'badge badge-info',
                'High': 'badge badge-warning',
                'Critical': 'badge badge-danger'
            };
            return badges[priority] || 'badge badge-secondary';
        },

        fetchConfigurations() {
            this.isLoading = true;
            let url = '/api/outlet-configurations?page=' + this.pagination.current_page;
            if (this.filterOutlet) url += '&outlet_id=' + this.filterOutlet;
            if (this.filterStatus) url += '&status=' + this.filterStatus;

            axios.get(url).then(response => {
                this.configurations = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch(error => {
                this.isLoading = false;
                this.$toaster.error('Failed to load configurations');
            });
        },

        fetchOutlets() {
            axios.get('/api/outlets/active').then(response => {
                this.outlets = response.data.outlets;
                this.activeOutlets = response.data.outlets.filter(o => o.Status === 'Active');
            }).catch(error => {});
        },

        searchData() {
            axios.get('/api/outlet-configurations/search/' + this.query + '?page=' + this.pagination.current_page)
                .then(response => {
                    this.configurations = response.data.data;
                    this.pagination = response.data.meta;
                }).catch(error => {});
        },

        filterByOutlet() {
            this.pagination.current_page = 1;
            this.fetchConfigurations();
        },

        filterByStatus() {
            this.pagination.current_page = 1;
            this.fetchConfigurations();
        },

        reload() {
            this.query = '';
            this.filterOutlet = '';
            this.filterStatus = '';
            this.pagination.current_page = 1;
            this.fetchConfigurations();
            this.$toaster.success('Data refreshed successfully');
        },

        openConfigModal() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            this.form.Priority = 'Medium';
            this.form.DatabasePort = '1433';
            this.selectedOutlet = null;
            this.selectedOutletIP = '';
            this.useCustomCredentials = false;
            $('#configModal').modal('show');
        },

        closeModal() {
            $('#configModal').modal('hide');
        },

        onOutletChange() {
            const outlet = this.outlets.find(o => o.OutletID == this.form.OutletID);
            if (outlet) {
                this.selectedOutlet = outlet;
                this.selectedOutletIP = outlet.IPAddress;
            } else {
                this.selectedOutlet = null;
                this.selectedOutletIP = '';
            }
        },

        testConnection() {
            if (!this.form.OutletID) {
                this.$toaster.warning('Please select an outlet first');
                return;
            }

            this.testingConnection = true;
            axios.post('/api/outlet-configurations/test-connection', {
                OutletID: this.form.OutletID,
                DatabaseName: this.form.DatabaseName,
                DatabasePort: this.form.DatabasePort,
                DatabaseUser: this.form.DatabaseUser,
                DatabasePassword: this.form.DatabasePassword,
            }).then(response => {
                this.testingConnection = false;
                if (response.data.success) {
                    this.$toaster.success('Connection successful!');
                } else {
                    this.$toaster.error('Connection failed: ' + response.data.message);
                }
            }).catch(error => {
                this.testingConnection = false;
                this.$toaster.error('Connection failed');
            });
        },

        storeConfig() {
            this.form.busy = true;
            this.form.post('/api/outlet-configurations').then(response => {
                $('#configModal').modal('hide');
                this.fetchConfigurations();
                this.$toaster.success('Configuration saved successfully');
            }).catch(error => {
                this.form.busy = false;
            });
        },

        editConfig(config) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(config);
            this.onOutletChange();
            this.useCustomCredentials = !!(config.DatabaseName || config.DatabaseUser);
            $('#configModal').modal('show');
        },

        updateConfig() {
            this.form.busy = true;
            this.form.put('/api/outlet-configurations/' + this.form.ConfigID).then(response => {
                $('#configModal').modal('hide');
                this.fetchConfigurations();
                this.$toaster.success('Configuration updated successfully');
            }).catch(error => {
                this.form.busy = false;
            });
        },

        viewConfig(config) {
            this.viewingConfig = config;
            $('#viewConfigModal').modal('show');
        },

        executeConfig(config) {
            Swal.fire({
                title: 'Execute Configuration?',
                html: `This will execute the script on <strong>${config.OutletName}</strong> database.<br><br>
                       <small class="text-muted">IP: ${config.IPAddress}</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Execute!'
            }).then(result => {
                if (result.isConfirmed) {
                    this.performExecution(config.ConfigID);
                }
            });
        },

        performExecution(configId) {
            Swal.fire({
                title: 'Executing...',
                html: 'Please wait while the script is being executed.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post('/api/outlet-configurations/' + configId + '/execute').then(response => {
                Swal.close();
                if (response.data.success) {
                    Swal.fire('Success!', 'Configuration executed successfully.', 'success');
                    this.fetchConfigurations();
                } else {
                    Swal.fire('Failed!', response.data.message, 'error');
                }
            }).catch(error => {
                Swal.close();
                Swal.fire('Error!', 'Failed to execute configuration.', 'error');
            });
        },

        executeFromView() {
            $('#viewConfigModal').modal('hide');
            this.executeConfig(this.viewingConfig);
        },

        deleteConfig(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This configuration will be permanently deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.delete('/api/outlet-configurations/' + id).then(response => {
                        this.fetchConfigurations();
                        Swal.fire('Deleted!', 'Configuration has been deleted.', 'success');
                    }).catch(error => {
                        Swal.fire('Error!', 'Failed to delete configuration.', 'error');
                    });
                }
            });
        },

        openBulkModal() {
            this.bulkForm = {
                selectedOutlets: [],
                ConfigType: '',
                Description: '',
                SqlScript: '',
            };
            this.selectAllOutlets = false;
            $('#bulkExecuteModal').modal('show');
        },

        toggleSelectAll() {
            if (this.selectAllOutlets) {
                this.bulkForm.selectedOutlets = this.activeOutlets.map(o => o.OutletID);
            } else {
                this.bulkForm.selectedOutlets = [];
            }
        },

        executeBulk() {
            if (this.bulkForm.selectedOutlets.length === 0) {
                this.$toaster.warning('Please select at least one outlet');
                return;
            }

            Swal.fire({
                title: 'Execute on ' + this.bulkForm.selectedOutlets.length + ' outlets?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                confirmButtonText: 'Yes, Execute All!'
            }).then(result => {
                if (result.isConfirmed) {
                    this.bulkExecuting = true;

                    axios.post('/api/outlet-configurations/bulk-execute', this.bulkForm).then(response => {
                        this.bulkExecuting = false;
                        $('#bulkExecuteModal').modal('hide');

                        if (response.data.success) {
                            Swal.fire({
                                title: 'Bulk Execution Complete',
                                html: `Success: ${response.data.success_count}<br>Failed: ${response.data.failed_count}`,
                                icon: response.data.failed_count > 0 ? 'warning' : 'success'
                            });
                            this.fetchConfigurations();
                        }
                    }).catch(error => {
                        this.bulkExecuting = false;
                        Swal.fire('Error!', 'Bulk execution failed', 'error');
                    });
                }
            });
        }
    }
}
</script>

<style scoped>
.font-monospace {
    font-family: 'Courier New', Courier, monospace;
    font-size: 13px;
}
.btn-xs {
    padding: 0.15rem 0.4rem;
    font-size: 0.75rem;
}
pre {
    white-space: pre-wrap;
    word-wrap: break-word;
}
</style>
