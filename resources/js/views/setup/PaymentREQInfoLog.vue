<!-- resources/js/pages/PaymentREQInfoLog.vue -->
<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Self Checker', 'Payment Request Log']"/>

            <!-- Alert if no outlet selected -->
            <div class="alert alert-warning" v-if="!hasSelectedOutlet">
                <i class="fas fa-exclamation-triangle"></i>
                Please select an outlet from the navbar to view payment logs
            </div>

            <div class="row" v-else>
                <!-- Statistics Cards -->
                <div class="col-xl-3 col-md-6" v-if="stats">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-receipt fa-3x"></i>
                                </div>
                                <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Total Transactions</h5>
                                <h4 class="font-weight-medium font-size-24">{{ stats.total_transactions || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6" v-if="stats">
                    <div class="card mini-stat bg-success text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-money-bill-wave fa-3x"></i>
                                </div>
                                <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Total Amount</h5>
                                <h4 class="font-weight-medium font-size-24">৳{{ formatMoney(stats.total_amount || 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6" v-if="stats">
                    <div class="card mini-stat bg-info text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-calendar-day fa-3x"></i>
                                </div>
                                <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Today's Transactions</h5>
                                <h4 class="font-weight-medium font-size-24">{{ stats.today_transactions || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6" v-if="stats">
                    <div class="card mini-stat bg-warning text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-hand-holding-usd fa-3x"></i>
                                </div>
                                <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Today's Amount</h5>
                                <h4 class="font-weight-medium font-size-24">৳{{ formatMoney(stats.today_amount || 0) }}</h4>
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
                                    <i class="fas fa-file-invoice-dollar mr-2"></i> Payment Request Logs
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
                                        <select v-model="filterPaymentType" class="form-control form-control-sm" @change="fetchLogs">
                                            <option value="">All Payment Types</option>
                                            <option :value="type" v-for="(type, index) in paymentTypes" :key="index">{{ type }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select v-model="filterTerminal" class="form-control form-control-sm" @change="fetchLogs">
                                            <option value="">All Terminals</option>
                                            <option :value="terminal" v-for="(terminal, index) in terminals" :key="index">{{ terminal }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input v-model="fromDate" type="date" class="form-control form-control-sm" @change="fetchLogs" placeholder="From Date">
                                    </div>
                                    <div class="col-md-2">
                                        <input v-model="toDate" type="date" class="form-control form-control-sm" @change="fetchLogs" placeholder="To Date">
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button type="button" class="btn btn-success btn-sm" @click="openModal">
                                            <i class="fas fa-plus"></i> Add Log
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
                                            <th>Terminal ID</th>
                                            <th>Payment Type</th>
                                            <th class="text-right">Amount</th>
                                            <th>Transaction Log ID</th>
                                            <th>Transaction ID</th>
                                            <th class="text-center">Request Log</th>
                                            <th class="text-center">Response Log</th>
                                            <th class="text-center">Created At</th>
                                            <th class="text-center" width="100">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(log, i) in logs" :key="log.id">
                                            <td class="text-center">{{ (pagination.current_page - 1) * pagination.per_page + (i + 1) }}</td>
                                            <td>
                                                <span class="badge badge-secondary">{{ log.terminal_id }}</span>
                                            </td>
                                            <td>
                                                    <span class="badge" :class="getPaymentTypeBadge(log.payment_type)">
                                                        {{ log.payment_type }}
                                                    </span>
                                            </td>
                                            <td class="text-right font-weight-bold">৳{{ formatMoney(log.amount) }}</td>
                                            <td><code>{{ log.tran_log_id || 'N/A' }}</code></td>
                                            <td><code>{{ log.tran_id || 'N/A' }}</code></td>
                                            <td class="text-center">
                                                <button v-if="log.tran_req_log" @click="viewLog('Request', log.tran_req_log)"
                                                        class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <span v-else class="text-muted">-</span>
                                            </td>
                                            <td class="text-center">
                                                <button v-if="log.tran_res_log" @click="viewLog('Response', log.tran_res_log)"
                                                        class="btn btn-success btn-sm">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <span v-else class="text-muted">-</span>
                                            </td>
                                            <td class="text-center">
                                                <small>{{ log.created_at | formatDateTime }}</small>
                                            </td>
                                            <td class="text-center">
                                                <button @click="edit(log)" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                                <button @click="destroy(log.id)" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!logs.length">
                                            <td colspan="10" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                No payment logs found
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="fetchLogs"
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
        <div class="modal fade" id="logModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i :class="editMode ? 'fas fa-edit' : 'fas fa-plus-circle'"></i>
                            {{ editMode ? "Edit" : "Add" }} Payment Log
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Terminal ID <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.terminal_id" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('terminal_id') }"
                                               placeholder="Enter terminal ID">
                                        <div class="error" v-if="form.errors.has('terminal_id')" v-html="form.errors.get('terminal_id')" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment Type <span class="text-danger">*</span></label>
                                        <select v-model="form.payment_type" class="form-control"
                                                :class="{ 'is-invalid': form.errors.has('payment_type') }">
                                            <option value="">Select Type</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Card">Card</option>
                                            <option value="bKash">bKash</option>
                                            <option value="Nagad">Nagad</option>
                                            <option value="Rocket">Rocket</option>
                                            <option value="SSL">SSL Commerce</option>
                                        </select>
                                        <div class="error" v-if="form.errors.has('payment_type')" v-html="form.errors.get('payment_type')" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Amount <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" v-model="form.amount" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('amount') }"
                                               placeholder="0.00">
                                        <div class="error" v-if="form.errors.has('amount')" v-html="form.errors.get('amount')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Transaction Log ID</label>
                                        <input type="text" v-model="form.tran_log_id" class="form-control"
                                               placeholder="Log ID">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Transaction ID</label>
                                        <input type="text" v-model="form.tran_id" class="form-control"
                                               placeholder="Transaction ID">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Transaction Request Log</label>
                                        <textarea v-model="form.tran_req_log" class="form-control" rows="4"
                                                  placeholder="Request log data (JSON or text)"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Transaction Response Log</label>
                                        <textarea v-model="form.tran_res_log" class="form-control" rows="4"
                                                  placeholder="Response log data (JSON or text)"></textarea>
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

        <!-- View Log Modal -->
        <div class="modal fade" id="viewLogModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-file-code"></i> {{ viewLogTitle }}
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <pre class="bg-light p-3 rounded" style="max-height: 500px; overflow-y: auto;">{{ viewLogContent }}</pre>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="copyToClipboard">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.title = 'Payment Request Log | Self Checker';

export default {
    name: "PaymentREQInfoLog",
    data() {
        return {
            logs: [],
            selectedOutlet: null,
            hasSelectedOutlet: false,
            paymentTypes: [],
            terminals: [],
            stats: null,
            pagination: {
                current_page: 1,
                per_page: 15
            },
            form: new Form({
                id: '',
                terminal_id: '',
                payment_type: '',
                amount: '',
                tran_log_id: '',
                tran_id: '',
                tran_req_log: '',
                tran_res_log: '',
            }),
            query: '',
            filterPaymentType: '',
            filterTerminal: '',
            fromDate: '',
            toDate: '',
            editMode: false,
            isLoading: false,
            viewLogTitle: '',
            viewLogContent: ''
        }
    },
    filters: {
        formatDateTime(value) {
            if (!value) return '-';
            const date = new Date(value);
            return date.toLocaleString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    },
    watch: {
        query: function(newQ) {
            if (newQ === '') {
                this.fetchLogs();
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
            this.fetchLogs();
            this.fetchPaymentTypes();
            this.fetchTerminals();
            this.fetchStats();
        });

        if (this.hasSelectedOutlet) {
            this.fetchLogs();
            this.fetchPaymentTypes();
            this.fetchTerminals();
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

        fetchLogs() {
            if (!this.hasSelectedOutlet) return;

            this.isLoading = true;
            let url = `/api/payment-logs?page=${this.pagination.current_page}`;
            if (this.filterPaymentType) url += `&payment_type=${this.filterPaymentType}`;
            if (this.filterTerminal) url += `&terminal_id=${this.filterTerminal}`;
            if (this.fromDate) url += `&from_date=${this.fromDate}`;
            if (this.toDate) url += `&to_date=${this.toDate}`;

            axios.get(url, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.logs = res.data.data;
                this.pagination = res.data.meta;
                this.isLoading = false;
            }).catch(err => {
                this.isLoading = false;
                this.$toaster.error(err.response?.data?.message || 'Failed to load logs');
            });
        },

        fetchPaymentTypes() {
            axios.get('/api/payment-logs/payment-types', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.paymentTypes = res.data.payment_types || [];
            }).catch(err => {});
        },

        fetchTerminals() {
            axios.get('/api/payment-logs/terminals', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.terminals = res.data.terminals || [];
            }).catch(err => {});
        },

        fetchStats() {
            axios.get('/api/payment-logs/stats', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.stats = res.data.stats;
            }).catch(err => {});
        },

        searchData() {
            if (!this.hasSelectedOutlet) return;

            axios.get(`/api/payment-logs?search=${this.query}&page=${this.pagination.current_page}`, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.logs = res.data.data;
                this.pagination = res.data.meta;
            }).catch(err => {});
        },

        reload() {
            this.query = '';
            this.filterPaymentType = '';
            this.filterTerminal = '';
            this.fromDate = '';
            this.toDate = '';
            this.pagination.current_page = 1;
            this.fetchLogs();
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
            $('#logModal').modal('show');
        },

        closeModal() {
            $('#logModal').modal('hide');
        },

        store() {
            this.form.busy = true;
            this.form.post('/api/payment-logs', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                $('#logModal').modal('hide');
                this.fetchLogs();
                this.fetchStats();
                this.$toaster.success('Payment log created successfully');
            }).catch(err => {
                this.form.busy = false;
            });
        },

        edit(log) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(log);
            $('#logModal').modal('show');
        },

        update() {
            this.form.busy = true;
            this.form.put(`/api/payment-logs/${this.form.id}`, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                $('#logModal').modal('hide');
                this.fetchLogs();
                this.fetchStats();
                this.$toaster.success('Payment log updated successfully');
            }).catch(err => {
                this.form.busy = false;
            });
        },

        destroy(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This log will be deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.delete(`/api/payment-logs/${id}`, {
                        headers: { 'X-Outlet-ID': this.getOutletId() }
                    }).then(res => {
                        this.fetchLogs();
                        this.fetchStats();
                        Swal.fire('Deleted!', 'Log has been deleted.', 'success');
                    }).catch(err => {
                        Swal.fire('Error!', 'Failed to delete log.', 'error');
                    });
                }
            });
        },

        viewLog(title, content) {
            this.viewLogTitle = `${title} Log`;
            this.viewLogContent = this.formatJSON(content);
            $('#viewLogModal').modal('show');
        },

        formatJSON(data) {
            try {
                return JSON.stringify(JSON.parse(data), null, 2);
            } catch (e) {
                return data;
            }
        },

        copyToClipboard() {
            navigator.clipboard.writeText(this.viewLogContent);
            this.$toaster.success('Copied to clipboard');
        },

        formatMoney(amount) {
            return parseFloat(amount || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        },

        getPaymentTypeBadge(type) {
            const badges = {
                'Cash': 'badge-success',
                'Card': 'badge-primary',
                'bKash': 'badge-danger',
                'Nagad': 'badge-warning',
                'Rocket': 'badge-info',
                'SSL': 'badge-secondary'
            };
            return badges[type] || 'badge-dark';
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
pre {
    font-size: 12px;
    line-height: 1.5;
}
</style>
