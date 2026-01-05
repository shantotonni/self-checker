<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Customer List']"/>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="datatable" v-if="!isLoading">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input v-model="query" type="text" class="form-control" placeholder="Search by name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-success btn-sm" @click="createCustomer">
                                            <i class="fas fa-plus"></i>
                                            Add Customer
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="exportCustomer">
                                            <i class="mdi mdi-calendar-export"></i>
                                            Export
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                            <i class="fas fa-sync"></i>
                                            Reload
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Customer Name</th>
                                            <th class="text-left">Mobile Number</th>
                                            <th class="text-left">Area</th>
                                            <th class="text-left">District</th>
                                            <th class="text-left">Upazilla Name</th>
                                            <th class="text-left">Email</th>
                                            <th class="text-left">Product Name</th>
                                            <th class="text-left">Model Name</th>
                                            <th class="text-left">Customer Code</th>
                                            <th class="text-left">Chassis</th>
                                            <th class="text-left">Registration Date</th>
                                            <th class="text-left">Last Service Hour</th>
                                            <th class="text-left">Image</th>
<!--                                            <th class="text-left"> Customer Type</th>-->
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(customer, i) in customers" :key="customer.id" v-if="customers.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ customer.name }}</td>
                                            <td>{{ customer.mobile }}</td>
                                            <td class="text-left">{{ customer.area_name }}</td>
                                            <td class="text-left">{{ customer.district_name }}</td>
                                            <td class="text-left">{{ customer.UpazillaName }}</td>
                                            <td>{{ customer.email }}</td>
                                            <td class="text-left">{{ customer.product_name }}</td>
                                            <td class="text-left">{{ customer.model}}</td>
                                            <td class="text-left">
                                                 <span v-for="(detail, i) in customer.customer_chassis" :key="detail.id" v-if="customer.customer_chassis.length">
                                                    <p style="margin: 0">Customer Code : {{detail.customer_code}}</p>
                                                </span>
                                            </td>
                                            <td class="text-left">
                                                <span v-for="(detail, i) in customer.customer_chassis" :key="detail.id" v-if="customer.customer_chassis.length">
                                                    <p style="margin: 0">Chassis no : {{detail.chassis_no}}</p>
                                                </span>
                                            </td>
                                            <td class="text-left">{{ customer.date }}</td>
                                            <td class="text-right">{{ customer.service_hour }}</td>
                                            <td class="text-left"><img v-if="customer.image" height="40" width="40" :src="tableImage(customer.image)" alt=""></td>
<!--                                            <td class="text-left">{{ customer.customer_type }}</td>-->
                                            <td class="text-left">
                                                <button @click="edit(customer)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                                               <button @click="resetModalOpen(customer)" class="btn btn-primary btn-sm"><i class="mdi mdi-lock-reset"></i>Reset Password</button>
<!--                                                <button @click="destroy(customer)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>&ndash;&gt;-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllCustomer() : searchData()"
                                    ></pagination>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <skeleton-loader :row="14"/>
                        </div>
                    </div>
                    <data-export/>
                </div>
            </div>
        </div>
        <!--  Modal content for the above example -->
        <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click.prevent="closeModal">×</button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Code</label>
                                            <input v-model="form.code" type="text" name="code" class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('code') }" >
                                            <div class="error" v-if="form.errors.has('code')"
                                                 v-html="form.errors.get('code')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Name</label>
                                            <input v-model="form.name" type="text" name="name" class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('name') }" >
                                            <div class="error" v-if="form.errors.has('name')"
                                                 v-html="form.errors.get('name')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Mobile</label>
                                            <input v-model="form.mobile" type="text" name="mobile"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('mobile') }">
                                            <div class="error" v-if="form.errors.has('mobile')"
                                                 v-html="form.errors.get('mobile')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Email</label>
                                            <input v-model="form.email" type="email" name="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                            <div class="error" v-if="form.errors.has('email')" v-html="form.errors.get('email')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6" v-if="!editMode">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password" v-model="form.password"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('password') }">
                                            <div class="error" v-if="form.errors.has('password')"
                                                 v-html="form.errors.get('password')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Area</label>
                                            <select name="text" id="area_id" class="form-control" v-model="form.area_id"
                                                    :class="{ 'is-invalid': form.errors.has('area_id') }">
                                                <option disabled value="">Select Area</option>
                                                <option :value="area.id" v-for="(area , index) in areas" :key="index">
                                                    {{ area.name }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('area_id')"
                                                 v-html="form.errors.get('area_id')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>District</label>
                                            <select name="text" id="district_id" class="form-control" v-model="form.district_id"
                                                    :class="{ 'is-invalid': form.errors.has('district_id') }">
                                                <option disabled value="">Select District</option>
                                                <option :value="district.id" v-for="(district , index) in districts"
                                                        :key="index">{{ district.name }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('district_id')"
                                                 v-html="form.errors.get('district_id')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Category</label>
                                            <select name="product_id" id="product_id" class="form-control"
                                                    v-model="form.product_id"
                                                    :class="{ 'is-invalid': form.errors.has('product_id') }"
                                                    @change="getAllModelByProduct()">
                                                <option disabled value="">Select Product Category</option>
                                                <option :value="product.id" v-for="(product , index) in products"
                                                        :key="index">{{ product.name_bn }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('product_id')"
                                                 v-html="form.errors.get('product_id')"/>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Model</label>
                                            <select name="text" id="model_id" class="form-control" v-model="form.model_id" :class="{ 'is-invalid': form.errors.has('model_id') }">
                                                <option disabled value="">Select Model</option>
                                                <option :value="model.id" v-for="(model , index) in models" :key="index">{{ model.model_name_bn }} </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('model_id')" v-html="form.errors.get('model_id')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image <small>(Image type:jpeg,jpg,png,svg)</small></label>
                                            <input @change="changeImage($event)" type="file" name="image"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('image') }">
                                            <div class="error" v-if="form.errors.has('image')"
                                                 v-html="form.errors.get('image')"/>
                                            <img v-if="form.image" :src="showImage(form.image)" alt="" height="40px"
                                                 width="40px">
                                        </div>
                                    </div>

<!--                                    <div class="col-md-6">-->
<!--                                        <div class="form-group">-->
<!--                                            <label>Service Hour</label>-->
<!--                                            <input v-model="form.service_hour" type="text" name="service_hour"-->
<!--                                                   class="form-control"-->
<!--                                                   :class="{ 'is-invalid': form.errors.has('service_hour') }">-->
<!--                                            <div class="error" v-if="form.errors.has('service_hour')"-->
<!--                                                 v-html="form.errors.get('service_hour')"/>-->
<!--                                        </div>-->
<!--                                    </div>-->

<!--                                    <div class="col-md-6">-->
<!--                                        <div class="form-group">-->
<!--                                            <label>Customer Address</label>-->
<!--                                            <input v-model="form.address" type="text" name="address"-->
<!--                                                   class="form-control"-->
<!--                                                   :class="{ 'is-invalid': form.errors.has('address') }">-->
<!--                                            <div class="error" v-if="form.errors.has('address')"-->
<!--                                                 v-html="form.errors.get('address')"/>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Chassis</label>
                                            <input type="text" name="chassis" v-model="form.chassis"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('chassis') }">
                                            <div class="error" v-if="form.errors.has('chassis')"
                                                 v-html="form.errors.get('chassis')"/>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                    @click.prevent="closeModal">Close
                            </button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                {{ editMode ? "Update" : "Create" }} Customer
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>

        <!--  Modal content for Reset Password -->
        <div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0">Reset Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click.prevent="closeModal">×</button>
                    </div>
                    <form @submit.prevent="updatePassword()" @keydown="form2.onKeydown($event)">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Enter New Password</label>
                                            <input v-model="form2.new_password" type="text" name="new_password" class="form-control" :class="{ 'is-invalid': form2.errors.has('new_password') }" >
                                            <div class="error" v-if="form2.errors.has('new_password')" v-html="form2.errors.get('new_password')"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click.prevent="closeModal">Close</button>
                            <button :disabled="form2.busy" type="submit" class="btn btn-primary">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from "vuejs-datepicker";
import {Common} from "../../mixins/common";
import {bus} from "../../app";
import moment from "moment";

document.title = 'Customer List | Harvester';
export default {
    name: "List",
    mixins: [Common],
    components: {
        Datepicker
    },
    data() {
        return {
            customers: [],
            districts: [],
            areas: [],
            products: [],
            models: [],
            companies: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                name: '',
                mobile: '',
                password: '',
                product_id: '',
                //service_hour: '',
                district_id: '',
                model_id: '',
                area_id: '',
                //address: '',
                chassis: '',
                image: '',
                email: '',
                code: '',
            }),
            form2: new Form({
                new_password: '',
                customer_id: '',
            }),
            isExport : ''
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllCustomer();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        this.getAllCustomer();
        this.getAllDistricts();
        this.getAllArea();
        //this.getAllModelByProduct();
        this.getAllProduct();
    },
    methods: {
        getAllCustomer() {
            this.isLoading = true;
            axios.get('/api/customer?page=' + this.pagination.current_page).then((response) => {
                this.customers = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/customer/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.customers = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllCustomer();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#customerModal").modal("hide");
        },
        createCustomer() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#customerModal").modal("show");
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/customer").then(response => {
                console.log(response.data)
                if (response.data.status === 'success'){
                    $("#customerModal").modal("hide");
                    this.$toaster.success(response.data.message);
                    this.getAllCustomer();
                }else {
                    this.$toaster.error(response.data.message);
                }
            }).catch(e => {
                this.$toaster.error(response.data.message);
                this.isLoading = false;
            });
        },
        edit(customer) {
            this.getAllModelByProduct();
            this.getAllProductModel();
            this.getAllProduct();
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(customer);
            $("#customerModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/customer/" + this.form.id).then(response => {
                $("#customerModal").modal("hide");
                this.getAllCustomer();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        resetModalOpen(customer){
            this.form2.customer_id = customer.id
            $("#resetPasswordModal").modal("show");
        },
        updatePassword(){
            this.form2.busy = true;
            this.form2.post("/api/customer-password-change-from-admin").then(response => {
                console.log(response.data)
                // if (response.data.status === 'success'){
                //     $("#customerModal").modal("hide");
                //     this.$toaster.success(response.data.message);
                //     this.getAllCustomer();
                // }else {
                //     this.$toaster.error(response.data.message);
                // }
            }).catch(e => {
                this.$toaster.error(response.data.message);
                this.isLoading = false;
            });
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
                return window.location.origin + "/harvester/public/images/customer/" + this.form.image;
            }
        },
        tableImage(image) {
            return window.location.origin + "/harvester/public/images/customer/" + image;
        },
        getAllProductModel() {
            axios.get('/api/get-all-product-model').then((response) => {
                this.models = response.data.data;
            }).catch((error) => {

            })
        },
        getAllProduct() {
            axios.get('/api/get-all-products').then((response) => {
                this.products = response.data.data;
            }).catch((error) => {

            })
        },
        getAllModelByProduct() {
            axios.get('/api/get-all-model-by-product/' + this.form.product_id).then((response) => {
                this.models = response.data.data;
            }).catch((error) => {

            })
        },
        getAllArea() {
            axios.get('/api/get-all-areas').then((response) => {
                this.areas = response.data.areas;
            }).catch((error) => {

            })
        },
        getAllDistricts() {
            axios.get('/api/get-all-districts').then((response) => {

                this.districts = response.data.districts;
            }).catch((error) => {

            })
        },
        customFormatter(date) {
            return moment(date).format('MMMM Do YYYY');
        },
        exportCustomer(){
            this.isExport = 'Y'
            axios.get('/api/customer?page=' + this.pagination.current_page
                + "&isExport=" + this.isExport
            ).then((response)=>{
                    let dataSets = response.data.data;
                console.log(dataSets)
                    if (dataSets.length > 0) {
                        let columns = Object.keys(dataSets[0]);
                        columns = columns.filter((item) => item !== 'row_num');
                        let rex = /([A-Z])([A-Z])([a-z])|([a-z])([A-Z])/g;
                        columns = columns.map((item) => {
                            let title = item.replace(rex, '$1$4 $2$3$5')
                            return {title, key: item}
                        });
                        bus.$emit('data-table-import', dataSets, columns, 'Customer Export')
                    }
                }).catch((error)=>{
            })
        },
    },
}
</script>

<style scoped>

</style>
