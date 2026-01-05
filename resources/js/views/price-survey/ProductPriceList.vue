<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Product Price List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                            <button type="button" class="btn btn-info btn-sm" @click="openImportModal">
                                <i class="fas fa-file-excel"></i>
                                Import Excel
                            </button>
                            <button type="button" class="btn btn-success btn-sm" @click="createProductPrice">
                                <i class="fas fa-plus"></i>
                                Add Price
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
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="small mb-1">Product</label>
                                                    <select v-model="filterProduct" class="form-control form-control-sm">
                                                        <option value="">All Products</option>
                                                        <option :value="product.productCode" v-for="(product, index) in products" :key="index">
                                                            {{ product.productName }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="small mb-1">Location</label>
                                                    <select v-model="filterLocation" class="form-control form-control-sm">
                                                        <option value="">All Locations</option>
                                                        <option :value="location.LocationId" v-for="(location, index) in locations" :key="index">
                                                            {{ location.LocationName }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="small mb-1">From Date</label>
                                                    <input type="date" v-model="filterFromDate" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="small mb-1">To Date</label>
                                                    <input type="date" v-model="filterToDate" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="small mb-1">&nbsp;</label>
                                                    <div>
                                                        <button type="button" @click="getAllProductPrices" class="btn btn-success btn-sm">
                                                            <i class="mdi mdi-filter"></i> Filter
                                                        </button>
                                                        <button type="button" @click="clearFilter" class="btn btn-secondary btn-sm">
                                                            <i class="mdi mdi-close"></i> Clear
                                                        </button>
                                                        <button type="button" @click="exportData" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-download"></i> Export
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <input v-model="query" type="text" class="form-control form-control-sm" placeholder="Search by product code">
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-center">SN</th>
                                            <th class="text-left">Product Code</th>
                                            <th class="text-left">Product Name</th>
                                            <th class="text-left">Location</th>
                                            <th class="text-right">Wholesale Price</th>
                                            <th class="text-right">Retail Price</th>
                                            <th class="text-center">Created By</th>
                                            <th class="text-center">Updated By</th>
                                            <th class="text-center">Created At</th>
                                            <th class="text-center">Updated At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(price, i) in productPrices" :key="price.productPriceId" v-if="productPrices.length">
                                            <td class="text-center">{{ getSerialNumber(i) }}</td>
                                            <td class="text-left">{{ price.productCode }}</td>
                                            <td class="text-left">{{ price.productName }}</td>
                                            <td class="text-left">{{ price.locationName }}</td>
                                            <td class="text-right">{{ formatCurrency(price.wPrice) }}</td>
                                            <td class="text-right">{{ formatCurrency(price.rPrice) }}</td>
                                            <td class="text-center">{{ price.createdBy }}</td>
                                            <td class="text-center">{{ price.updatedBy }}</td>
                                            <td class="text-center">{{ formatDate(price.createdAt) }}</td>
                                            <td class="text-center">{{ formatDate(price.updatedAt) }}</td>
                                            <td class="text-center">
                                                <button @click="edit(price)" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                                <button @click="viewHistory(price)" class="btn btn-info btn-sm" title="View History">
                                                    <i class="fas fa-history"></i>
                                                </button>
                                                <button @click="destroy(price.productPriceId)" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!productPrices.length">
                                            <td colspan="11" class="text-center">No data available</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllProductPrices() : searchData()"
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
        <div class="modal fade" id="productPriceModal" tabindex="-1" role="dialog" aria-labelledby="productPriceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productPriceModalLabel">{{ editMode ? "Edit" : "Add" }} Product Price</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">
                            ×
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Product <span class="text-danger">*</span></label>
                                        <select v-model="form.productCode"
                                                class="form-control"
                                                :class="{ 'is-invalid': form.errors.has('productCode') }"
                                                :disabled="editMode">
                                            <option disabled value="">Select Product</option>
                                            <option :value="product.productCode" v-for="(product, index) in products" :key="index">
                                                {{ product.productCode }} - {{ product.productName }}
                                            </option>
                                        </select>
                                        <div class="error" v-if="form.errors.has('productCode')" v-html="form.errors.get('productCode')"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Location <span class="text-danger">*</span></label>
                                        <select v-model="form.locationId"
                                                class="form-control"
                                                :class="{ 'is-invalid': form.errors.has('locationId') }"
                                                :disabled="editMode">
                                            <option disabled value="">Select Location</option>
                                            <option :value="location.LocationId" v-for="(location, index) in locations" :key="index">
                                                {{ location.LocationName }}
                                            </option>
                                        </select>
                                        <div class="error" v-if="form.errors.has('locationId')" v-html="form.errors.get('locationId')"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Wholesale Price (WPrice) <span class="text-danger">*</span></label>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               v-model="form.wPrice"
                                               class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('wPrice') }"
                                               placeholder="Enter wholesale price">
                                        <div class="error" v-if="form.errors.has('wPrice')" v-html="form.errors.get('wPrice')"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Retail Price (RPrice) <span class="text-danger">*</span></label>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               v-model="form.rPrice"
                                               class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('rPrice') }"
                                               placeholder="Enter retail price">
                                        <div class="error" v-if="form.errors.has('rPrice')" v-html="form.errors.get('rPrice')"/>
                                    </div>
                                </div>
                            </div>
                            <!-- Price Difference Info -->
                            <div class="row" v-if="form.wPrice && form.rPrice">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <strong>Price Difference:</strong> {{ formatCurrency(form.rPrice - form.wPrice) }}
                                        <span v-if="form.wPrice > 0">
                                            ({{ ((form.rPrice - form.wPrice) / form.wPrice * 100).toFixed(2) }}% markup)
                                        </span>
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
                                {{ editMode ? "Update" : "Create" }} Price
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal for Price History -->
        <div class="modal fade" id="priceHistoryModal" tabindex="-1" role="dialog" aria-labelledby="priceHistoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="priceHistoryModalLabel">Price History - {{ selectedProduct }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            ×
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="text-right">Wholesale Price</th>
                                    <th class="text-right">Retail Price</th>
                                    <th>Updated By</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(history, index) in priceHistory" :key="index">
                                    <td>{{ formatDate(history.updatedAt) }}</td>
                                    <td class="text-right">{{ formatCurrency(history.wPrice) }}</td>
                                    <td class="text-right">{{ formatCurrency(history.rPrice) }}</td>
                                    <td>{{ history.updatedBy }}</td>
                                </tr>
                                <tr v-if="!priceHistory.length">
                                    <td colspan="4" class="text-center">No history available</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Excel Import -->
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="importModalLabel">
                            <i class="fas fa-file-excel"></i> Import Product Prices from Excel
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true" @click="closeImportModal">
                            ×
                        </button>
                    </div>
                    <form @submit.prevent="importExcel">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Location <span class="text-danger">*</span></label>
                                        <select v-model="importForm.locationId" class="form-control" :class="{ 'is-invalid': importErrors.locationId }">
                                            <option disabled value="">Select Location</option>
                                            <option :value="location.LocationId" v-for="(location, index) in locations" :key="index">
                                                {{ location.LocationName }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback" v-if="importErrors.locationId">{{ importErrors.locationId }}</div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Excel File <span class="text-danger">*</span></label>
                                        <div class="custom-file">
                                            <input type="file"
                                                   class="custom-file-input"
                                                   id="excelFile"
                                                   accept=".xlsx,.xls,.csv"
                                                   @change="handleFileUpload"
                                                   :class="{ 'is-invalid': importErrors.file }">
                                            <label class="custom-file-label" for="excelFile">
                                                {{ importForm.fileName || 'Choose file...' }}
                                            </label>
                                        </div>
                                        <div class="text-danger small" v-if="importErrors.file">{{ importErrors.file }}</div>
                                        <small class="text-muted">Supported formats: .xlsx, .xls, .csv</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Excel Format Guide -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-header py-2">
                                            <strong><i class="fas fa-info-circle"></i> Excel Format Guide</strong>
                                            <button type="button" class="btn btn-sm btn-outline-primary float-right" @click="downloadSampleExcel">
                                                <i class="fas fa-download"></i> Download Sample
                                            </button>
                                        </div>
                                        <div class="card-body py-2">
                                            <p class="mb-2 small">Your Excel file should have the following columns:</p>
                                            <table class="table table-bordered table-sm small mb-0">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th>ProductCode</th>
                                                    <th>WPrice</th>
                                                    <th>RPrice</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>PRD001</td>
                                                    <td>100.00</td>
                                                    <td>120.00</td>
                                                </tr>
                                                <tr>
                                                    <td>PRD002</td>
                                                    <td>200.00</td>
                                                    <td>250.00</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Import Progress -->
                            <div class="row mt-3" v-if="importProgress.show">
                                <div class="col-md-12">
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                             :class="importProgress.status === 'error' ? 'bg-danger' : 'bg-success'"
                                             role="progressbar"
                                             :style="{ width: importProgress.percent + '%' }">
                                            {{ importProgress.percent }}%
                                        </div>
                                    </div>
                                    <p class="text-center mt-2 mb-0">{{ importProgress.message }}</p>
                                </div>
                            </div>

                            <!-- Import Result -->
                            <div class="row mt-3" v-if="importResult.show">
                                <div class="col-md-12">
                                    <div :class="'alert alert-' + (importResult.success ? 'success' : 'danger')">
                                        <h6><i :class="importResult.success ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i> Import Result</h6>
                                        <ul class="mb-0">
                                            <li>Total Rows: {{ importResult.total }}</li>
                                            <li>Successfully Imported: {{ importResult.imported }}</li>
                                            <li>Updated: {{ importResult.updated }}</li>
                                            <li v-if="importResult.failed > 0" class="text-danger">Failed: {{ importResult.failed }}</li>
                                        </ul>
                                        <div v-if="importResult.errors && importResult.errors.length > 0" class="mt-2">
                                            <strong>Errors:</strong>
                                            <ul class="mb-0">
                                                <li v-for="(error, index) in importResult.errors" :key="index" class="text-danger small">
                                                    {{ error }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeImportModal">
                                Close
                            </button>
                            <button :disabled="importForm.busy || !importForm.file || !importForm.locationId" type="submit" class="btn btn-info">
                                <i v-if="importForm.busy" class="fas fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-upload"></i>
                                Import Data
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
    name: "ProductPriceList",
    data() {
        return {
            productPrices: [],
            products: [],
            locations: [],
            priceHistory: [],
            selectedProduct: '',
            pagination: {
                current_page: 1,
                last_page: 1,
                per_page: 15,
                total: 0
            },
            query: "",
            filterProduct: "",
            filterLocation: "",
            filterFromDate: "",
            filterToDate: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                productPriceId: '',
                productCode: '',
                locationId: '',
                wPrice: '',
                rPrice: ''
            }),
            // Import form data
            importForm: {
                locationId: '',
                file: null,
                fileName: '',
                busy: false
            },
            importErrors: {},
            importProgress: {
                show: false,
                percent: 0,
                message: '',
                status: ''
            },
            importResult: {
                show: false,
                success: false,
                total: 0,
                imported: 0,
                updated: 0,
                failed: 0,
                errors: []
            }
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllProductPrices();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Product Price List | Group Expense';
        this.getAllProductPrices();
        this.getProducts();
        this.getLocations();
    },
    methods: {
        getAllProductPrices() {
            this.isLoading = true;
            let params = new URLSearchParams();
            params.append('page', this.pagination.current_page);

            if (this.filterProduct) {
                params.append('product_code', this.filterProduct);
            }
            if (this.filterLocation) {
                params.append('location_id', this.filterLocation);
            }
            if (this.filterFromDate) {
                params.append('from_date', this.filterFromDate);
            }
            if (this.filterToDate) {
                params.append('to_date', this.filterToDate);
            }

            axios.get('/api/product-prices?' + params.toString())
                .then((response) => {
                    this.productPrices = response.data.data;
                    this.pagination = response.data.meta;
                    this.isLoading = false;
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.$toaster.error('Failed to load product prices');
                    console.error(error);
                });
        },

        searchData() {
            this.isLoading = true;
            axios.get("/api/product-prices/search/" + this.query + "?page=" + this.pagination.current_page)
                .then(response => {
                    this.productPrices = response.data.data;
                    this.pagination = response.data.meta;
                    this.isLoading = false;
                })
                .catch(e => {
                    this.isLoading = false;
                    console.error(e);
                });
        },

        getProducts() {
            axios.get('/api/product-prices/products')
                .then((response) => {
                    this.products = response.data.products;
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        getLocations() {
            axios.get('/api/product-prices/locations')
                .then((response) => {
                    this.locations = response.data.locations;
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        reload() {
            this.clearFilter();
            this.getAllProductPrices();
            this.$toaster.success('Data Successfully Refreshed');
        },

        clearFilter() {
            this.filterProduct = "";
            this.filterLocation = "";
            this.filterFromDate = "";
            this.filterToDate = "";
            this.query = "";
            this.pagination.current_page = 1;
        },

        closeModal() {
            $("#productPriceModal").modal("hide");
        },

        createProductPrice() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#productPriceModal").modal("show");
        },

        store() {
            this.form.busy = true;
            this.form.post("/api/product-prices")
                .then(response => {
                    $("#productPriceModal").modal("hide");
                    this.getAllProductPrices();
                    this.$toaster.success('Product price created successfully');
                })
                .catch(e => {
                    this.form.busy = false;
                    console.error(e);
                });
        },

        edit(price) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill({
                productPriceId: price.productPriceId,
                productCode: price.productCode,
                locationId: price.locationId,
                wPrice: price.wPrice,
                rPrice: price.rPrice
            });
            $("#productPriceModal").modal("show");
        },

        update() {
            this.form.busy = true;
            this.form.put("/api/product-prices/" + this.form.productPriceId)
                .then(response => {
                    $("#productPriceModal").modal("hide");
                    this.getAllProductPrices();
                    this.$toaster.success('Product price updated successfully');
                })
                .catch(e => {
                    this.form.busy = false;
                    console.error(e);
                });
        },

        viewHistory(price) {
            this.selectedProduct = price.productCode + ' - ' + price.productName;
            axios.get('/api/product-prices/' + price.productPriceId + '/history')
                .then((response) => {
                    this.priceHistory = response.data.history;
                    $("#priceHistoryModal").modal("show");
                })
                .catch((error) => {
                    this.$toaster.error('Failed to load price history');
                    console.error(error);
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
                    axios.delete('/api/product-prices/' + id)
                        .then((response) => {
                            this.getAllProductPrices();
                            Swal.fire('Deleted!', 'Product price has been deleted.', 'success');
                        })
                        .catch((error) => {
                            this.$toaster.error('Failed to delete product price');
                            console.error(error);
                        });
                }
            });
        },

        // ==================== IMPORT METHODS ====================
        openImportModal() {
            this.resetImportForm();
            $("#importModal").modal("show");
        },

        closeImportModal() {
            this.resetImportForm();
            $("#importModal").modal("hide");
        },

        resetImportForm() {
            this.importForm = {
                locationId: '',
                file: null,
                fileName: '',
                busy: false
            };
            this.importErrors = {};
            this.importProgress = {
                show: false,
                percent: 0,
                message: '',
                status: ''
            };
            this.importResult = {
                show: false,
                success: false,
                total: 0,
                imported: 0,
                updated: 0,
                failed: 0,
                errors: []
            };
            // Reset file input
            const fileInput = document.getElementById('excelFile');
            if (fileInput) {
                fileInput.value = '';
            }
        },

        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const allowedExtensions = ['xlsx', 'xls', 'csv'];
                const fileExtension = file.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(fileExtension)) {
                    this.importErrors.file = 'Invalid file format. Please upload .xlsx, .xls, or .csv file';
                    this.importForm.file = null;
                    this.importForm.fileName = '';
                    return;
                }

                this.importForm.file = file;
                this.importForm.fileName = file.name;
                this.importErrors.file = '';
            }
        },

        importExcel() {
            // Validate
            this.importErrors = {};
            if (!this.importForm.locationId) {
                this.importErrors.locationId = 'Please select a location';
                return;
            }
            if (!this.importForm.file) {
                this.importErrors.file = 'Please select an Excel file';
                return;
            }

            this.importForm.busy = true;
            this.importProgress = {
                show: true,
                percent: 0,
                message: 'Uploading file...',
                status: 'progress'
            };
            this.importResult.show = false;

            const formData = new FormData();
            formData.append('file', this.importForm.file);
            formData.append('location_id', this.importForm.locationId);

            axios.post('/api/product-prices/import', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: (progressEvent) => {
                    this.importProgress.percent = Math.round((progressEvent.loaded * 50) / progressEvent.total);
                    this.importProgress.message = 'Uploading file...';
                }
            })
                .then(response => {
                    this.importProgress.percent = 100;
                    this.importProgress.message = 'Import completed!';
                    this.importProgress.status = 'success';

                    this.importResult = {
                        show: true,
                        success: true,
                        total: response.data.total || 0,
                        imported: response.data.imported || 0,
                        updated: response.data.updated || 0,
                        failed: response.data.failed || 0,
                        errors: response.data.errors || []
                    };

                    this.importForm.busy = false;
                    this.getAllProductPrices();
                    this.$toaster.success('Import completed successfully');
                })
                .catch(error => {
                    this.importProgress.percent = 100;
                    this.importProgress.message = 'Import failed!';
                    this.importProgress.status = 'error';

                    this.importResult = {
                        show: true,
                        success: false,
                        total: 0,
                        imported: 0,
                        updated: 0,
                        failed: 0,
                        errors: [error.response?.data?.message || 'An error occurred during import']
                    };

                    this.importForm.busy = false;
                    this.$toaster.error('Import failed');
                    console.error(error);
                });
        },

        downloadSampleExcel() {
            axios.get('/api/product-prices/sample-excel', {
                responseType: 'blob'
            })
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'product_price_sample.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                })
                .catch(error => {
                    this.$toaster.error('Failed to download sample file');
                    console.error(error);
                });
        },

        exportData() {
            let params = new URLSearchParams();
            if (this.filterProduct) params.append('product_code', this.filterProduct);
            if (this.filterLocation) params.append('location_id', this.filterLocation);
            if (this.filterFromDate) params.append('from_date', this.filterFromDate);
            if (this.filterToDate) params.append('to_date', this.filterToDate);

            axios.get('/api/product-prices/export?' + params.toString(), {
                responseType: 'blob'
            })
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'product_prices_' + new Date().toISOString().slice(0,10) + '.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    this.$toaster.success('Export completed');
                })
                .catch(error => {
                    this.$toaster.error('Failed to export data');
                    console.error(error);
                });
        },

        // ==================== UTILITY METHODS ====================
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

        formatCurrency(value) {
            if (value === null || value === undefined) return '-';
            return new Intl.NumberFormat('en-BD', {
                style: 'currency',
                currency: 'BDT',
                minimumFractionDigits: 2
            }).format(value);
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
    min-width: 200px;
}

.alert-info {
    padding: 10px 15px;
    margin-bottom: 0;
}

.custom-file-label::after {
    content: "Browse";
}

.form-control-sm {
    height: calc(1.5em + 0.5rem + 2px);
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
