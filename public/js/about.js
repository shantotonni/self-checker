"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["about"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/customer/List.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/customer/DeliveryInfoList.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
document.title = 'Customer List | Bill Printing';
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: "List",
  data: function data() {
    return {
      customers: [],
      companies: [],
      pagination: {
        current_page: 1
      },
      query: "",
      editMode: false,
      isLoading: false,
      form: new Form({
        CustomerID: '',
        CompanyID: '',
        CustomerCode: '',
        CustomerName: '',
        CustomerAddress: '',
        CustomerEmail: '',
        CustomerMobile: '',
        CustomerPersonName: '',
        CustomerPersonMobile: ''
      })
    };
  },
  watch: {
    query: function query(newQ, old) {
      if (newQ === "") {
        this.getAllCustomer();
      } else {
        this.searchData();
      }
    }
  },
  mounted: function mounted() {
    this.getAllCustomer();
  },
  methods: {
    getAllCustomer: function getAllCustomer() {
      var _this = this;

      this.isLoading = true;
      axios.get('/api/customer?page=' + this.pagination.current_page).then(function (response) {
        _this.customers = response.data.data;
        _this.pagination = response.data.meta;
        _this.isLoading = false;
      })["catch"](function (error) {});
    },
    searchData: function searchData() {
      var _this2 = this;

      axios.get("/api/search/customer/" + this.query + "?page=" + this.pagination.current_page).then(function (response) {
        _this2.customers = response.data.data;
        _this2.pagination = response.data.meta;
      })["catch"](function (e) {
        _this2.isLoading = false;
      });
    },
    reload: function reload() {
      this.getAllCustomer();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal: function closeModal() {
      $("#customerModal").modal("hide");
    },
    createCustomer: function createCustomer() {
      this.getAllCompany();
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#customerModal").modal("show");
    },
    store: function store() {
      var _this3 = this;

      this.form.busy = true;
      this.form.post("/api/customer").then(function (response) {
        $("#customerModal").modal("hide");

        _this3.getAllCustomer();
      })["catch"](function (e) {
        _this3.isLoading = false;
      });
    },
    edit: function edit(customer) {
      this.getAllCompany();
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(customer);
      $("#customerModal").modal("show");
    },
    update: function update() {
      var _this4 = this;

      this.form.busy = true;
      this.form.put("/api/customer/" + this.form.CustomerID).then(function (response) {
        $("#customerModal").modal("hide");

        _this4.getAllCustomer();
      })["catch"](function (e) {
        _this4.isLoading = false;
      });
    },
    getAllCompany: function getAllCompany() {
      var _this5 = this;

      axios.get('/api/get-company').then(function (response) {
        _this5.companies = response.data.data;
      })["catch"](function (error) {});
    }
  }
});

/***/ }),

/***/ "./resources/js/views/customer/List.vue":
/*!**********************************************!*\
  !*** ./resources/js/views/customer/DeliveryInfoList.vue ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _List_vue_vue_type_template_id_3ef76e60_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./DeliveryInfoList.vue?vue&type=template&id=3ef76e60&scoped=true& */ "./resources/js/views/customer/List.vue?vue&type=template&id=3ef76e60&scoped=true&");
/* harmony import */ var _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DeliveryInfoList.vue?vue&type=script&lang=js& */ "./resources/js/views/customer/List.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _List_vue_vue_type_template_id_3ef76e60_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _List_vue_vue_type_template_id_3ef76e60_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "3ef76e60",
  null

)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/customer/DeliveryInfoList.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/views/customer/List.vue?vue&type=script&lang=js&":
/*!***********************************************************************!*\
  !*** ./resources/js/views/customer/DeliveryInfoList.vue?vue&type=script&lang=js& ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./DeliveryInfoList.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/customer/List.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]);

/***/ }),

/***/ "./resources/js/views/customer/List.vue?vue&type=template&id=3ef76e60&scoped=true&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/views/customer/DeliveryInfoList.vue?vue&type=template&id=3ef76e60&scoped=true& ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_3ef76e60_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_3ef76e60_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_3ef76e60_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./DeliveryInfoList.vue?vue&type=template&id=3ef76e60&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/customer/List.vue?vue&type=template&id=3ef76e60&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/customer/List.vue?vue&type=template&id=3ef76e60&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/customer/DeliveryInfoList.vue?vue&type=template&id=3ef76e60&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "content" }, [
    _c(
      "div",
      { staticClass: "container-fluid" },
      [
        _c("breadcrumb", { attrs: { options: ["Customer List"] } }),
        _vm._v(" "),
        _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col-xl-12" }, [
            _c("div", { staticClass: "card" }, [
              !_vm.isLoading
                ? _c("div", { staticClass: "datatable" }, [
                    _c("div", { staticClass: "card-body" }, [
                      _c("div", { staticClass: "d-flex" }, [
                        _c("div", { staticClass: "flex-grow-1" }, [
                          _c("div", { staticClass: "row" }, [
                            _c("div", { staticClass: "col-md-2" }, [
                              _c("input", {
                                directives: [
                                  {
                                    name: "model",
                                    rawName: "v-model",
                                    value: _vm.query,
                                    expression: "query",
                                  },
                                ],
                                staticClass: "form-control",
                                attrs: { type: "text", placeholder: "Search" },
                                domProps: { value: _vm.query },
                                on: {
                                  input: function ($event) {
                                    if ($event.target.composing) {
                                      return
                                    }
                                    _vm.query = $event.target.value
                                  },
                                },
                              }),
                            ]),
                          ]),
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "card-tools" }, [
                          _c(
                            "button",
                            {
                              staticClass: "btn btn-success btn-sm",
                              attrs: { type: "button" },
                              on: { click: _vm.createCustomer },
                            },
                            [
                              _c("i", { staticClass: "fas fa-plus" }),
                              _vm._v(
                                "\n                                            Add Customer\n                                        "
                              ),
                            ]
                          ),
                          _vm._v(" "),
                          _c(
                            "button",
                            {
                              staticClass: "btn btn-primary btn-sm",
                              attrs: { type: "button" },
                              on: { click: _vm.reload },
                            },
                            [
                              _c("i", { staticClass: "fas fa-sync" }),
                              _vm._v(
                                "\n                                            Reload\n                                        "
                              ),
                            ]
                          ),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "table-responsive" },
                        [
                          _c(
                            "table",
                            {
                              staticClass:
                                "table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small",
                            },
                            [
                              _vm._m(0),
                              _vm._v(" "),
                              _c(
                                "tbody",
                                _vm._l(_vm.customers, function (customer, i) {
                                  return _vm.customers.length
                                    ? _c("tr", { key: customer.CustomerID }, [
                                        _c(
                                          "th",
                                          {
                                            staticClass: "text-center",
                                            attrs: { scope: "row" },
                                          },
                                          [_vm._v(_vm._s(++i))]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          { staticClass: "text-center" },
                                          [_vm._v(_vm._s(customer.CompanyName))]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          { staticClass: "text-center" },
                                          [
                                            _vm._v(
                                              _vm._s(customer.CustomerCode)
                                            ),
                                          ]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          { staticClass: "text-center" },
                                          [
                                            _vm._v(
                                              _vm._s(customer.CustomerName)
                                            ),
                                          ]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          { staticClass: "text-center" },
                                          [
                                            _vm._v(
                                              _vm._s(customer.CustomerAddress)
                                            ),
                                          ]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          { staticClass: "text-center" },
                                          [
                                            _vm._v(
                                              _vm._s(customer.CustomerEmail)
                                            ),
                                          ]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          { staticClass: "text-center" },
                                          [
                                            _vm._v(
                                              _vm._s(customer.CustomerMobile)
                                            ),
                                          ]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          { staticClass: "text-center" },
                                          [
                                            _vm._v(
                                              _vm._s(
                                                customer.CustomerPersonName
                                              )
                                            ),
                                          ]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          { staticClass: "text-center" },
                                          [
                                            _vm._v(
                                              _vm._s(
                                                customer.CustomerPersonMobile
                                              )
                                            ),
                                          ]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          { staticClass: "text-center" },
                                          [
                                            customer.Active == "Y"
                                              ? _c(
                                                  "span",
                                                  {
                                                    staticClass:
                                                      "badge badge-success",
                                                  },
                                                  [_vm._v("Active")]
                                                )
                                              : _c("span", {
                                                  staticClass:
                                                    "badge badge-danger",
                                                }),
                                          ]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          { staticClass: "text-center" },
                                          [
                                            _c(
                                              "button",
                                              {
                                                staticClass:
                                                  "btn btn-success btn-sm",
                                                on: {
                                                  click: function ($event) {
                                                    return _vm.edit(customer)
                                                  },
                                                },
                                              },
                                              [
                                                _c("i", {
                                                  staticClass: "far fa-edit",
                                                }),
                                              ]
                                            ),
                                          ]
                                        ),
                                      ])
                                    : _vm._e()
                                }),
                                0
                              ),
                            ]
                          ),
                          _vm._v(" "),
                          _c("br"),
                          _vm._v(" "),
                          _vm.pagination.last_page > 1
                            ? _c("pagination", {
                                attrs: {
                                  pagination: _vm.pagination,
                                  offset: 5,
                                },
                                on: {
                                  paginate: function ($event) {
                                    _vm.query === ""
                                      ? _vm.getAllCustomer()
                                      : _vm.searchData()
                                  },
                                },
                              })
                            : _vm._e(),
                        ],
                        1
                      ),
                    ]),
                  ])
                : _c("div", [_c("skeleton-loader", { attrs: { row: 14 } })], 1),
            ]),
          ]),
        ]),
      ],
      1
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        staticClass: "modal fade",
        attrs: {
          id: "customerModal",
          tabindex: "-1",
          role: "dialog",
          "aria-labelledby": "myLargeModalLabel",
          "aria-hidden": "true",
        },
      },
      [
        _c("div", { staticClass: "modal-dialog modal-lg" }, [
          _c("div", { staticClass: "modal-content" }, [
            _c("div", { staticClass: "modal-header" }, [
              _c(
                "h5",
                {
                  staticClass: "modal-title mt-0",
                  attrs: { id: "myLargeModalLabel" },
                },
                [_vm._v(_vm._s(_vm.editMode ? "Edit" : "Add") + " Customer")]
              ),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "close",
                  attrs: {
                    type: "button",
                    "data-dismiss": "modal",
                    "aria-hidden": "true",
                  },
                  on: {
                    click: function ($event) {
                      $event.preventDefault()
                      return _vm.closeModal.apply(null, arguments)
                    },
                  },
                },
                [_vm._v("×")]
              ),
            ]),
            _vm._v(" "),
            _c(
              "form",
              {
                on: {
                  submit: function ($event) {
                    $event.preventDefault()
                    _vm.editMode ? _vm.update() : _vm.store()
                  },
                  keydown: function ($event) {
                    return _vm.form.onKeydown($event)
                  },
                },
              },
              [
                _c("div", { staticClass: "modal-body" }, [
                  _c("div", { staticClass: "col-md-12" }, [
                    _c("div", { staticClass: "row" }, [
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", [_vm._v("Company")]),
                          _vm._v(" "),
                          _c(
                            "select",
                            {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.form.CompanyID,
                                  expression: "form.CompanyID",
                                },
                              ],
                              staticClass: "form-control",
                              class: {
                                "is-invalid": _vm.form.errors.has("CompanyID"),
                              },
                              attrs: { name: "CompanyID", id: "CompanyID" },
                              on: {
                                change: function ($event) {
                                  var $$selectedVal = Array.prototype.filter
                                    .call($event.target.options, function (o) {
                                      return o.selected
                                    })
                                    .map(function (o) {
                                      var val =
                                        "_value" in o ? o._value : o.value
                                      return val
                                    })
                                  _vm.$set(
                                    _vm.form,
                                    "CompanyID",
                                    $event.target.multiple
                                      ? $$selectedVal
                                      : $$selectedVal[0]
                                  )
                                },
                              },
                            },
                            [
                              _c(
                                "option",
                                { attrs: { disabled: "", value: "" } },
                                [_vm._v("Select Company")]
                              ),
                              _vm._v(" "),
                              _vm._l(_vm.companies, function (company, index) {
                                return _c(
                                  "option",
                                  {
                                    key: index,
                                    domProps: { value: company.CompanyID },
                                  },
                                  [_vm._v(_vm._s(company.CompanyName))]
                                )
                              }),
                            ],
                            2
                          ),
                          _vm._v(" "),
                          _vm.form.errors.has("CompanyID")
                            ? _c("div", {
                                staticClass: "error",
                                domProps: {
                                  innerHTML: _vm._s(
                                    _vm.form.errors.get("CompanyID")
                                  ),
                                },
                              })
                            : _vm._e(),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", [_vm._v("Customer Code")]),
                          _vm._v(" "),
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.form.CustomerCode,
                                expression: "form.CustomerCode",
                              },
                            ],
                            staticClass: "form-control",
                            class: {
                              "is-invalid": _vm.form.errors.has("CustomerCode"),
                            },
                            attrs: { type: "text", name: "CustomerCode" },
                            domProps: { value: _vm.form.CustomerCode },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.form,
                                  "CustomerCode",
                                  $event.target.value
                                )
                              },
                            },
                          }),
                          _vm._v(" "),
                          _vm.form.errors.has("CustomerCode")
                            ? _c("div", {
                                staticClass: "error",
                                domProps: {
                                  innerHTML: _vm._s(
                                    _vm.form.errors.get("CustomerCode")
                                  ),
                                },
                              })
                            : _vm._e(),
                        ]),
                      ]),
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "row" }, [
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", [_vm._v("Customer Name")]),
                          _vm._v(" "),
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.form.CustomerName,
                                expression: "form.CustomerName",
                              },
                            ],
                            staticClass: "form-control",
                            class: {
                              "is-invalid": _vm.form.errors.has("CustomerName"),
                            },
                            attrs: { type: "text", name: "CustomerName" },
                            domProps: { value: _vm.form.CustomerName },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.form,
                                  "CustomerName",
                                  $event.target.value
                                )
                              },
                            },
                          }),
                          _vm._v(" "),
                          _vm.form.errors.has("CustomerName")
                            ? _c("div", {
                                staticClass: "error",
                                domProps: {
                                  innerHTML: _vm._s(
                                    _vm.form.errors.get("CustomerName")
                                  ),
                                },
                              })
                            : _vm._e(),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", [_vm._v("Customer Address")]),
                          _vm._v(" "),
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.form.CustomerAddress,
                                expression: "form.CustomerAddress",
                              },
                            ],
                            staticClass: "form-control",
                            class: {
                              "is-invalid":
                                _vm.form.errors.has("CustomerAddress"),
                            },
                            attrs: { type: "text", name: "CustomerAddress" },
                            domProps: { value: _vm.form.CustomerAddress },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.form,
                                  "CustomerAddress",
                                  $event.target.value
                                )
                              },
                            },
                          }),
                          _vm._v(" "),
                          _vm.form.errors.has("CustomerAddress")
                            ? _c("div", {
                                staticClass: "error",
                                domProps: {
                                  innerHTML: _vm._s(
                                    _vm.form.errors.get("CustomerAddress")
                                  ),
                                },
                              })
                            : _vm._e(),
                        ]),
                      ]),
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "row" }, [
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", [_vm._v("Customer Email")]),
                          _vm._v(" "),
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.form.CustomerEmail,
                                expression: "form.CustomerEmail",
                              },
                            ],
                            staticClass: "form-control",
                            class: {
                              "is-invalid":
                                _vm.form.errors.has("CustomerEmail"),
                            },
                            attrs: { type: "text", name: "CustomerEmail" },
                            domProps: { value: _vm.form.CustomerEmail },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.form,
                                  "CustomerEmail",
                                  $event.target.value
                                )
                              },
                            },
                          }),
                          _vm._v(" "),
                          _vm.form.errors.has("CustomerEmail")
                            ? _c("div", {
                                staticClass: "error",
                                domProps: {
                                  innerHTML: _vm._s(
                                    _vm.form.errors.get("CustomerEmail")
                                  ),
                                },
                              })
                            : _vm._e(),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", [_vm._v("Customer Mobile")]),
                          _vm._v(" "),
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.form.CustomerMobile,
                                expression: "form.CustomerMobile",
                              },
                            ],
                            staticClass: "form-control",
                            class: {
                              "is-invalid":
                                _vm.form.errors.has("CustomerMobile"),
                            },
                            attrs: { type: "number", name: "CustomerMobile" },
                            domProps: { value: _vm.form.CustomerMobile },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.form,
                                  "CustomerMobile",
                                  $event.target.value
                                )
                              },
                            },
                          }),
                          _vm._v(" "),
                          _vm.form.errors.has("CustomerMobile")
                            ? _c("div", {
                                staticClass: "error",
                                domProps: {
                                  innerHTML: _vm._s(
                                    _vm.form.errors.get("CustomerMobile")
                                  ),
                                },
                              })
                            : _vm._e(),
                        ]),
                      ]),
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "row" }, [
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", [_vm._v("Customer Person Name")]),
                          _vm._v(" "),
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.form.CustomerPersonName,
                                expression: "form.CustomerPersonName",
                              },
                            ],
                            staticClass: "form-control",
                            class: {
                              "is-invalid":
                                _vm.form.errors.has("CustomerPersonName"),
                            },
                            attrs: { type: "text", name: "CustomerPersonName" },
                            domProps: { value: _vm.form.CustomerPersonName },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.form,
                                  "CustomerPersonName",
                                  $event.target.value
                                )
                              },
                            },
                          }),
                          _vm._v(" "),
                          _vm.form.errors.has("CustomerPersonName")
                            ? _c("div", {
                                staticClass: "error",
                                domProps: {
                                  innerHTML: _vm._s(
                                    _vm.form.errors.get("CustomerPersonName")
                                  ),
                                },
                              })
                            : _vm._e(),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-6" }, [
                        _c("div", { staticClass: "form-group" }, [
                          _c("label", [_vm._v("Customer Person Mobile")]),
                          _vm._v(" "),
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.form.CustomerPersonMobile,
                                expression: "form.CustomerPersonMobile",
                              },
                            ],
                            staticClass: "form-control",
                            class: {
                              "is-invalid": _vm.form.errors.has(
                                "CustomerPersonMobile"
                              ),
                            },
                            attrs: {
                              type: "text",
                              name: "CustomerPersonMobile",
                            },
                            domProps: { value: _vm.form.CustomerPersonMobile },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.form,
                                  "CustomerPersonMobile",
                                  $event.target.value
                                )
                              },
                            },
                          }),
                          _vm._v(" "),
                          _vm.form.errors.has("CustomerPersonMobile")
                            ? _c("div", {
                                staticClass: "error",
                                domProps: {
                                  innerHTML: _vm._s(
                                    _vm.form.errors.get("CustomerPersonMobile")
                                  ),
                                },
                              })
                            : _vm._e(),
                        ]),
                      ]),
                    ]),
                  ]),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "modal-footer" }, [
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-secondary",
                      attrs: { type: "button", "data-dismiss": "modal" },
                      on: {
                        click: function ($event) {
                          $event.preventDefault()
                          return _vm.closeModal.apply(null, arguments)
                        },
                      },
                    },
                    [_vm._v("Close")]
                  ),
                  _vm._v(" "),
                  _c(
                    "button",
                    {
                      staticClass: "btn btn-primary",
                      attrs: { disabled: _vm.form.busy, type: "submit" },
                    },
                    [
                      _vm._v(
                        _vm._s(_vm.editMode ? "Update" : "Create") + " Customer"
                      ),
                    ]
                  ),
                ]),
              ]
            ),
          ]),
        ]),
      ]
    ),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", { staticClass: "text-center" }, [_vm._v("SN")]),
        _vm._v(" "),
        _c("th", { staticClass: "text-center" }, [_vm._v("Company Name")]),
        _vm._v(" "),
        _c("th", { staticClass: "text-center" }, [_vm._v("Customer Code")]),
        _vm._v(" "),
        _c("th", { staticClass: "text-center" }, [_vm._v("Customer Name")]),
        _vm._v(" "),
        _c("th", { staticClass: "text-center" }, [_vm._v("Customer Address")]),
        _vm._v(" "),
        _c("th", { staticClass: "text-center" }, [_vm._v("Customer Email")]),
        _vm._v(" "),
        _c("th", { staticClass: "text-center" }, [_vm._v("Customer Mobile")]),
        _vm._v(" "),
        _c("th", { staticClass: "text-center" }, [
          _vm._v("Customer Person Name"),
        ]),
        _vm._v(" "),
        _c("th", { staticClass: "text-center" }, [
          _vm._v("Customer Person Mobile"),
        ]),
        _vm._v(" "),
        _c("th", { staticClass: "text-center" }, [_vm._v("Status")]),
        _vm._v(" "),
        _c("th", { staticClass: "text-center" }, [_vm._v("Action")]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ })

}]);
