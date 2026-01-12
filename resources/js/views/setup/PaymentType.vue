<!-- resources/js/pages/PaymentType.vue -->
<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Self Checker', 'Payment Type']"/>

            <!-- Alert if no outlet selected -->
            <div class="alert alert-warning" v-if="!hasSelectedOutlet">
                <i class="fas fa-exclamation-triangle"></i>
                Please select an outlet from the navbar to manage payment types
            </div>

            <div class="row" v-else>
                <!-- Statistics Cards -->
                <div class="col-xl-2 col-md-4" v-if="stats">
                    <div class="card mini-stat bg-primary text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-terminal fa-2x"></i>
                                </div>
                                <h5 class="font-size-14 text-uppercase mt-0 text-white-50">Total Terminals</h5>
                                <h4 class="font-weight-medium font-size-20">{{ stats.total_terminals || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4" v-if="stats">
                    <div class="card mini-stat bg-success text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-credit-card fa-2x"></i>
                                </div>
                                <h5 class="font-size-14 text-uppercase mt-0 text-white-50">CITY POS</h5>
                                <h4 class="font-weight-medium font-size-20">{{ stats.city_pos_enabled || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4" v-if="stats">
                    <div class="card mini-stat bg-danger text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-qrcode fa-2x"></i>
                                </div>
                                <h5 class="font-size-14 text-uppercase mt-0 text-white-50">bKash QR</h5>
                                <h4 class="font-weight-medium font-size-20">{{ stats.bkash_qr_enabled || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4" v-if="stats">
                    <div class="card mini-stat bg-info text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-credit-card fa-2x"></i>
                                </div>
                                <h5 class="font-size-14 text-uppercase mt-0 text-white-50">EBL POS</h5>
                                <h4 class="font-weight-medium font-size-20">{{ stats.ebl_pos_enabled || 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4" v-if="stats">
                    <div class="card mini-stat bg-warning text-white">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left mini-stat-img mr-4">
                                    <i class="fas fa-qrcode fa-2x"></i>
                                </div>
                                <h5 class="font-size-14 text-uppercase mt-0 text-white-50">Bangla QR MTB</h5>
                                <h4 class="font-weight-medium font-size-20">{{ stats.bangla_qr_mtb_enabled || 0 }}</h4>
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
                                    <i class="fas fa-cash-register mr-2"></i> Payment Type Configuration
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
                                        <input v-model="query" type="text" class="form-control form-control-sm" placeholder="Search terminal...">
                                    </div>
                                    <div class="col-md-2">
                                        <select v-model="filterTerminal" class="form-control form-control-sm" @change="fetchPaymentTypes">
                                            <option value="">All Terminals</option>
                                            <option :value="terminal" v-for="(terminal, index) in terminals" :key="index">{{ terminal }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5"></div>
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
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center" width="50">SN</th>
                                            <th>Terminal ID</th>
                                            <th class="text-center" width="150">
                                                <i class="fas fa-credit-card"></i> CITY POS
                                            </th>
                                            <th class="text-center" width="150">
                                                <i class="fas fa-qrcode"></i> bKash QR
                                            </th>
                                            <th class="text-center" width="150">
                                                <i class="fas fa-credit-card"></i> EBL POS
                                            </th>
                                            <th class="text-center" width="150">
                                                <i class="fas fa-qrcode"></i> Bangla QR MTB
                                            </th>
                                            <th class="text-center" width="120">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(payment, i) in paymentTypes" :key="payment.id">
                                            <td class="text-center">{{ (pagination.current_page - 1) * pagination.per_page + (i + 1) }}</td>
                                            <td>
                                                <span class="badge badge-secondary">{{ payment.terminal_id }}</span>
                                            </td>
                                            <td class="text-center">
                                                <button @click="toggleMethod(payment.id, 'CITY_POS')"
                                                        class="btn btn-sm w-100"
                                                        :class="payment.CITY_POS == 1 ? 'btn-success' : 'btn-outline-secondary'">
                                                    <i :class="payment.CITY_POS == 1 ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                                    {{ payment.CITY_POS == 1 ? 'Enabled' : 'Disabled' }}
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <button @click="toggleMethod(payment.id, 'BKASH_QR')"
                                                        class="btn btn-sm w-100"
                                                        :class="payment.BKASH_QR == 1 ? 'btn-danger' : 'btn-outline-secondary'">
                                                    <i :class="payment.BKASH_QR == 1 ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                                    {{ payment.BKASH_QR == 1 ? 'Enabled' : 'Disabled' }}
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <button @click="toggleMethod(payment.id, 'EBL_POS')"
                                                        class="btn btn-sm w-100"
                                                        :class="payment.EBL_POS == 1 ? 'btn-info' : 'btn-outline-secondary'">
                                                    <i :class="payment.EBL_POS == 1 ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                                    {{ payment.EBL_POS == 1 ? 'Enabled' : 'Disabled' }}
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <button @click="toggleMethod(payment.id, 'BANGLA_QR_MTB')"
                                                        class="btn btn-sm w-100"
                                                        :class="payment.BANGLA_QR_MTB == 1 ? 'btn-warning' : 'btn-outline-secondary'">
                                                    <i :class="payment.BANGLA_QR_MTB == 1 ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                                    {{ payment.BANGLA_QR_MTB == 1 ? 'Enabled' : 'Disabled' }}
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <button @click="edit(payment)" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                                <button @click="destroy(payment.id)" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!paymentTypes.length">
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                No payment type configurations found
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="fetchPaymentTypes"
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
        <div class="modal fade" id="paymentTypeModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i :class="editMode ? 'fas fa-edit' : 'fas fa-plus-circle'"></i>
                            {{ editMode ? "Edit" : "Add" }} Payment Type
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" @click="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Terminal ID <span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.terminal_id" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('terminal_id') }"
                                               placeholder="Enter terminal ID">
                                        <div class="error" v-if="form.errors.has('terminal_id')" v-html="form.errors.get('terminal_id')" />
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="text-primary mb-3"><i class="fas fa-toggle-on"></i> Enable Payment Methods</h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-lg">
                                            <input type="checkbox" class="custom-control-input" id="cityPosSwitch"
                                                   v-model="form.CITY_POS" true-value="1" false-value="0">
                                            <label class="custom-control-label" for="cityPosSwitch">
                                                <i class="fas fa-credit-card mr-2 text-success"></i>
                                                <strong>CITY POS</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted">Enable City Bank POS Terminal</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-lg">
                                            <input type="checkbox" class="custom-control-input" id="bkashQrSwitch"
                                                   v-model="form.BKASH_QR" true-value="1" false-value="0">
                                            <label class="custom-control-label" for="bkashQrSwitch">
                                                <i class="fas fa-qrcode mr-2 text-danger"></i>
                                                <strong>bKash QR</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted">Enable bKash QR Code Payment</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-lg">
                                            <input type="checkbox" class="custom-control-input" id="eblPosSwitch"
                                                   v-model="form.EBL_POS" true-value="1" false-value="0">
                                            <label class="custom-control-label" for="eblPosSwitch">
                                                <i class="fas fa-credit-card mr-2 text-info"></i>
                                                <strong>EBL POS</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted">Enable EBL POS Terminal</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-lg">
                                            <input type="checkbox" class="custom-control-input" id="banglaQrSwitch"
                                                   v-model="form.BANGLA_QR_MTB" true-value="1" false-value="0">
                                            <label class="custom-control-label" for="banglaQrSwitch">
                                                <i class="fas fa-qrcode mr-2 text-warning"></i>
                                                <strong>Bangla QR MTB</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted">Enable Bangla QR MTB Payment</small>
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
document.title = 'Payment Type | Self Checker';

export default {
    name: "PaymentType",
    data() {
        return {
            paymentTypes: [],
            selectedOutlet: null,
            hasSelectedOutlet: false,
            terminals: [],
            stats: null,
            pagination: {
                current_page: 1,
                per_page: 15
            },
            form: new Form({
                id: '',
                terminal_id: '',
                CITY_POS: 0,
                BKASH_QR: 0,
                EBL_POS: 0,
                BANGLA_QR_MTB: 0,
            }),
            query: '',
            filterTerminal: '',
            editMode: false,
            isLoading: false
        }
    },
    watch: {
        query: function(newQ) {
            if (newQ === '') {
                this.fetchPaymentTypes();
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
            this.fetchPaymentTypes();
            this.fetchTerminals();
            this.fetchStats();
        });

        if (this.hasSelectedOutlet) {
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

        fetchPaymentTypes() {
            if (!this.hasSelectedOutlet) return;

            this.isLoading = true;
            let url = `/api/payment-types?page=${this.pagination.current_page}`;
            if (this.filterTerminal) url += `&terminal_id=${this.filterTerminal}`;

            axios.get(url, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.paymentTypes = res.data.data;
                this.pagination = res.data.meta;
                this.isLoading = false;
            }).catch(err => {
                this.isLoading = false;
                this.$toaster.error(err.response?.data?.message || 'Failed to load payment types');
            });
        },

        fetchTerminals() {
            axios.get('/api/payment-types/terminals', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.terminals = res.data.terminals || [];
            }).catch(err => {});
        },

        fetchStats() {
            axios.get('/api/payment-types/stats', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.stats = res.data.stats;
            }).catch(err => {});
        },

        searchData() {
            if (!this.hasSelectedOutlet) return;

            axios.get(`/api/payment-types?search=${this.query}&page=${this.pagination.current_page}`, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.paymentTypes = res.data.data;
                this.pagination = res.data.meta;
            }).catch(err => {});
        },

        reload() {
            this.query = '';
            this.filterTerminal = '';
            this.pagination.current_page = 1;
            this.fetchPaymentTypes();
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
            this.form.CITY_POS = 0;
            this.form.BKASH_QR = 0;
            this.form.EBL_POS = 0;
            this.form.BANGLA_QR_MTB = 0;
            $('#paymentTypeModal').modal('show');
        },

        closeModal() {
            $('#paymentTypeModal').modal('hide');
        },

        store() {
            this.form.busy = true;
            this.form.post('/api/payment-types', {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                $('#paymentTypeModal').modal('hide');
                this.fetchPaymentTypes();
                this.fetchStats();
                this.$toaster.success('Payment type created successfully');
            }).catch(err => {
                this.form.busy = false;
            });
        },

        edit(payment) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(payment);
            $('#paymentTypeModal').modal('show');
        },

        update() {
            this.form.busy = true;
            this.form.put(`/api/payment-types/${this.form.id}`, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                $('#paymentTypeModal').modal('hide');
                this.fetchPaymentTypes();
                this.fetchStats();
                this.$toaster.success('Payment type updated successfully');
            }).catch(err => {
                this.form.busy = false;
            });
        },

        destroy(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This configuration will be deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.delete(`/api/payment-types/${id}`, {
                        headers: { 'X-Outlet-ID': this.getOutletId() }
                    }).then(res => {
                        this.fetchPaymentTypes();
                        this.fetchStats();
                        Swal.fire('Deleted!', 'Configuration has been deleted.', 'success');
                    }).catch(err => {
                        Swal.fire('Error!', 'Failed to delete configuration.', 'error');
                    });
                }
            });
        },

        toggleMethod(id, method) {
            axios.post(`/api/payment-types/${id}/toggle`, {
                method: method
            }, {
                headers: { 'X-Outlet-ID': this.getOutletId() }
            }).then(res => {
                this.fetchPaymentTypes();
                this.fetchStats();
                this.$toaster.success('Payment method toggled successfully');
            }).catch(err => {
                this.$toaster.error('Failed to toggle payment method');
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
.custom-switch-lg .custom-control-label {
    padding-left: 2rem;
    padding-top: 0.25rem;
}
.custom-switch-lg .custom-control-label::before {
    height: 1.5rem;
    width: 3rem;
    border-radius: 3rem;
}
.custom-switch-lg .custom-control-label::after {
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 3rem;
}
.custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after {
    transform: translateX(1.5rem);
}
</style>
