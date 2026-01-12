<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Self Checker', 'Payment Success Info']"/>

            <!-- Alert if no outlet selected -->
            <div class="alert alert-warning" v-if="!hasSelectedOutlet">
                <i class="fas fa-exclamation-triangle"></i>
                Please select an outlet from the navbar to view payment information
            </div>

            <div class="row" v-else>
                <!-- Statistics Cards -->
                <div class="col-xl-3 col-md-6" v-if="stats">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-check-circle fa-3x"></i>
                                </div>
                                <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Total Success</h5>
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
                                <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Today's Success</h5>
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
                                    <i class="fas fa-credit-card mr-2"></i> Payment Success Information
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
                                        <select v-model="filterCardType" class="form-control form-control-sm" @change="fetchPayments">
                                            <option value="">All Card Types</option>
                                            <option :value="type" v-for="(type, index) in cardTypes" :key="index">{{ type }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select v-model="filterIssuer" class="form-control form-control-sm" @change="fetchPayments">
                                            <option value="">All Issuers</option>
                                            <option :value="issuer" v-for="(issuer, index) in issuers" :key="index">{{ issuer }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input v-model="fromDate" type="date" class="form-control form-control-sm" @change="fetchPayments">
                                    </div>
                                    <div class="col-md-2">
                                        <input v-model="toDate" type="date" class="form-control form-control-sm" @change="fetchPayments">
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button type="button" class="btn btn-success btn-sm" @click="openModal">
                                            <i class="fas fa-plus"></i> Add
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
                                            <th>Invoice No</th>
                                            <th class="text-right">Amount</th>
                                            <th>Card No</th>
                                            <th>Card Type</th>
                                            <th>Issuer</th>
                                            <th>Auth Code</th>
                                            <th>Ref No</th>
                                            <th>Terminal</th>
                                            <th>Merchant</th>
                                            <th>Trans Time</th>
                                            <th class="text-center">Created</th>
                                            <th class="text-center" width="100">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(payment, i) in payments" :key="payment.Id">
                                            <td class="text-center">{{ (pagination.current_page - 1) * pagination.per_page + (i + 1) }}</td>
                                            <td>
                                                <span class="badge badge-primary">{{ payment.InvoiceNo }}</span>
                                            </td>
                                            <td class="text-right font-weight-bold text-success">৳{{ formatMoney(payment.Amount) }}</td>
                                            <td>
                                                <code>{{ maskCardNumber(payment.CardNo) }}</code>
                                            </td>
                                            <td>
                                                    <span class="badge" :class="getCardTypeBadge(payment.CardType)">
                                                        {{ payment.CardType || '-' }}
                                                    </span>
                                            </td>
                                            <td>{{ payment.IssuerName || '-' }}</td>
                                            <td><code>{{ payment.AuthCode || '-' }}</code></td>
                                            <td><small>{{ payment.RefNo || '-' }}</small></td>
                                            <td>
                                                <span class="badge badge-secondary" v-if="payment.TerminalId">{{ payment.TerminalId }}</span>
                                                <span v-else>-</span>
                                            </td>
                                            <td><small>{{ payment.MerchantName || '-' }}</small></td>
                                            <td><small>{{ payment.TransTime || '-' }}</small></td>
                                            <td class="text-center">
                                                <small>{{ payment.CreatedAt | formatDateTime }}</small>
                                            </td>
                                            <td class="text-center">
                                                <button @click="viewDetails(payment)" class="btn btn-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button @click="edit(payment)" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                                <button @click="destroy(payment.Id)" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!payments.length">
                                            <td colspan="13" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                No payment records found
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="fetchPayments"
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
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i :class="editMode ? 'fas fa-edit' : 'fas fa-plus-circle'"></i>
                            {{ editMode ? "Edit" : "Add" }} Payment Info
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <!-- Transaction Details -->
                            <h6 class="text-primary mb-3"><i class="fas fa-file-invoice"></i> Transaction Details</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Invoice No <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.InvoiceNo" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('InvoiceNo') }"
                                               placeholder="INV-001">
                                        <div class="error" v-if="form.errors.has('InvoiceNo')" v-html="form.errors.get('InvoiceNo')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Amount <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" v-model="form.Amount" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('Amount') }"
                                               placeholder="0.00">
                                        <div class="error" v-if="form.errors.has('Amount')" v-html="form.errors.get('Amount')" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Transaction Time</label>
                                        <input type="text" v-model="form.TransTime" class="form-control"
                                               placeholder="YYYY-MM-DD HH:MM:SS">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="text-primary mb-3"><i class="fas fa-credit-card"></i> Card Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Card Number</label>
                                        <input type="text" v-model="form.CardNo" class="form-control"
                                               placeholder="**** **** **** 1234">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Card Type</label>
                                        <select v-model="form.CardType" class="form-control">
                                            <option value="">Select Type</option>
                                            <option value="Visa">Visa</option>
                                            <option value="MasterCard">MasterCard</option>
                                            <option value="AmEx">American Express</option>
                                            <option value="Debit">Debit Card</option>
                                            <option value="Credit">Credit Card</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Issuer Name</label>
                                        <input type="text" v-model="form.IssuerName" class="form-control"
                                               placeholder="Bank/Issuer Name">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="text-primary mb-3"><i class="fas fa-building"></i> Merchant & Terminal</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Merchant ID</label>
                                        <input type="text" v-model="form.MerchantId" class="form-control"
                                               placeholder="Merchant ID">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Merchant Name</label>
                                        <input type="text" v-model="form.MerchantName" class="form-control"
                                               placeholder="Merchant Name">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Terminal ID</label>
                                        <input type="text" v-model="form.TerminalId" class="form-control"
                                               placeholder="Terminal ID">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>SDK Version</label>
                                        <input type="text" v-model="form.SdkVersion" class="form-control"
                                               placeholder="v1.0.0">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="text-primary mb-3"><i class="fas fa-receipt"></i> Additional Information</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Auth Code</label>
                                        <input type="text" v-model="form.AuthCode" class="form-control"
                                               placeholder="Authorization Code">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Batch No</label>
                                        <input type="text" v-model="form.BatchNo" class="form-control"
                                               placeholder="Batch Number">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Reference No</label>
                                        <input type="text" v-model="form.RefNo" class="form-control"
                                               placeholder="Reference Number">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Voucher No</label>
                                        <input type="text" v-model="form.VoucherNo" class="form-control"
                                               placeholder="Voucher Number">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Transaction Log ID</label>
                                        <input type="text" v-model="form.tran_log_id" class="form-control"
                                               placeholder="Transaction Log ID">
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

        <!-- View Details Modal -->
        <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-info-circle"></i> Payment Details
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body" v-if="selectedPayment">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="150">Invoice No:</th>
                                        <td><span class="badge badge-primary">{{ selectedPayment.InvoiceNo }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Amount:</th>
                                        <td class="text-success font-weight-bold">৳{{ formatMoney(selectedPayment.Amount) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Card Number:</th>
                                        <td><code>{{ maskCardNumber(selectedPayment.CardNo) }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Card Type:</th>
                                        <td><span class="badge" :class="getCardTypeBadge(selectedPayment.CardType)">{{ selectedPayment.CardType || '-' }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Issuer:</th>
                                        <td>{{ selectedPayment.IssuerName || '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Auth Code:</th>
                                        <td><code>{{ selectedPayment.AuthCode || '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Batch No:</th>
                                        <td>{{ selectedPayment.BatchNo || '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="150">Reference No:</th>
                                        <td>{{ selectedPayment.RefNo || '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Voucher No:</th>
                                        <td>{{ selectedPayment.VoucherNo || '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Terminal ID:</th>
                                        <td><span class="badge badge-secondary">{{ selectedPayment.TerminalId || '-' }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Merchant ID:</th>
                                        <td>{{ selectedPayment.MerchantId || '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Merchant Name:</th>
                                        <td>{{ selectedPayment.MerchantName || '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Trans Time:</th>
                                        <td>{{ selectedPayment.TransTime || '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>SDK Version:</th>
                                        <td>{{ selectedPayment.SdkVersion || '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row" v-if="selectedPayment.tran_log_id">
                            <div class="col-md-12">
                                <hr>
                                <h6><i class="fas fa-link"></i> Transaction Log ID</h6>
                                <p><code>{{ selectedPayment.tran_log_id }}</code></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.title = 'Payment Success Info | Self Checker';

export default {
    name: "PaymentSuccessInfo",
    data() {
        return {
            payments: [],
            selectedOutlet: null,
            selectedPayment: null,
            hasSelectedOutlet: false,
            cardTypes: [],
            issuers: [],
            terminals: [],
            stats: null,
            pagination: {
                current_page: 1,
                per_page: 15
            },
            form: new Form({
                Id: '',
                InvoiceNo: '',
                Amount: '',
                AuthCode: '',
                BatchNo: '',
                CardNo: '',
                CardType: '',
                IssuerName: '',
                MerchantId: '',
                MerchantName: '',
                RefNo: '',
                TerminalId: '',
                TransTime: '',
                VoucherNo: '',
                SdkVersion: '',
                tran_log_id: '',
            }),
            query: '',
            filterCardType: '',
            filterIssuer: '',
            fromDate: '',
            toDate: '',
            editMode: false,
            isLoading: false
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
                this.fetchPayments();
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
            this.fetchPayments();
            this.fetchFilters();
            this.fetchStats();
        });

        if (this.hasSelectedOutlet) {
            this.fetchPayments();
            this.fetchFilters();
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

        fetchPayments() {
            if (!this.hasSelectedOutlet) return;

            this.isLoading = true;
            let url = `/api/payment-success?page=${this.pagination.current_page}`;
            if (this.filterCardType) url += `&card_type=${this.filterCardType}`;
            if (this.filterIssuer) url += `&issuer_name=${this.filterIssuer}`;
            if (this.fromDate) url += `&from_date=${this.fromDate}`;
            if (this.toDate) url += `&to_date=${this.toDate}`;

            axios.get(url, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.payments = res.data.data;
                this.pagination = res.data.meta;
                this.isLoading = false;
            }).catch(err => {
                this.isLoading = false;
                this.$toaster.error(err.response?.data?.message || 'Failed to load payments');
            });
        },

        fetchFilters() {
            axios.get('/api/payment-success/card-types', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.cardTypes = res.data.card_types || [];
            }).catch(err => {});

            axios.get('/api/payment-success/issuers', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.issuers = res.data.issuers || [];
            }).catch(err => {});

            axios.get('/api/payment-success/terminals', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.terminals = res.data.terminals || [];
            }).catch(err => {});
        },

        fetchStats() {
            axios.get('/api/payment-success/stats', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.stats = res.data.stats;
            }).catch(err => {});
        },

        searchData() {
            if (!this.hasSelectedOutlet) return;

            axios.get(`/api/payment-success?search=${this.query}&page=${this.pagination.current_page}`, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.payments = res.data.data;
                this.pagination = res.data.meta;
            }).catch(err => {});
        },

        reload() {
            this.query = '';
            this.filterCardType = '';
            this.filterIssuer = '';
            this.fromDate = '';
            this.toDate = '';
            this.pagination.current_page = 1;
            this.fetchPayments();
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
            $('#paymentModal').modal('show');
        },

        closeModal() {
            $('#paymentModal').modal('hide');
        },

        store() {
            this.form.busy = true;
            this.form.post('/api/payment-success', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                $('#paymentModal').modal('hide');
                this.fetchPayments();
                this.fetchStats();
                this.$toaster.success('Payment info created successfully');
            }).catch(err => {
                this.form.busy = false;
            });
        },

        edit(payment) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(payment);
            $('#paymentModal').modal('show');
        },

        update() {
            this.form.busy = true;
            this.form.put(`/api/payment-success/${this.form.Id}`, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                $('#paymentModal').modal('hide');
                this.fetchPayments();
                this.fetchStats();
                this.$toaster.success('Payment info updated successfully');
            }).catch(err => {
                this.form.busy = false;
            });
        },

        destroy(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This payment record will be deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.delete(`/api/payment-success/${id}`, {
                        headers: { 'X-Outlet-ID': this.getOutletId() }
                    }).then(res => {
                        this.fetchPayments();
                        this.fetchStats();
                        Swal.fire('Deleted!', 'Record has been deleted.', 'success');
                    }).catch(err => {
                        Swal.fire('Error!', 'Failed to delete record.', 'error');
                    });
                }
            });
        },

        viewDetails(payment) {
            this.selectedPayment = payment;
            $('#detailsModal').modal('show');
        },

        formatMoney(amount) {
            return parseFloat(amount || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        },

        maskCardNumber(cardNo) {
            if (!cardNo) return 'N/A';
            if (cardNo.length <= 4) return cardNo;
            return '**** **** **** ' + cardNo.slice(-4);
        },

        getCardTypeBadge(type) {
            const badges = {
                'Visa': 'badge-primary',
                'MasterCard': 'badge-warning',
                'AmEx': 'badge-info',
                'Debit': 'badge-success',
                'Credit': 'badge-danger'
            };
            return badges[type] || 'badge-secondary';
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
