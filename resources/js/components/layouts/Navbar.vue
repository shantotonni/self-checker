<template>
    <div class="topbar">
        <div class="topbar-left">
            <router-link :to="{name:'Dashboard'}" class="logo" style="line-height: 67px;color: white">
                Self Checker
            </router-link>
        </div>
        <nav class="navbar-custom">
            <ul class="navbar-right list-inline float-right mb-0">

                <!-- ✅ Outlet Dropdown -->
                <li class="dropdown notification-list list-inline-item">
                    <a class="nav-link dropdown-toggle waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-store noti-icon"></i>
                        <span class="d-none d-sm-inline-block ml-1">
                            {{ selectedOutlet ? selectedOutlet.OutletName : 'Select Outlet' }}
                        </span>
                        <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated" style="width: 320px;">
                        <!-- Search -->
                        <div class="p-2 border-bottom">
                            <input type="text" class="form-control form-control-sm"
                                   v-model="outletSearch" placeholder="Search outlet...">
                        </div>

                        <!-- Current Connection -->
                        <div class="p-2 bg-light border-bottom" v-if="selectedOutlet">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs mr-2">
                                    <span class="avatar-title rounded-circle bg-success text-white">
                                        <i class="mdi mdi-check"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-size-13">{{ selectedOutlet.OutletName }}</h6>
                                    <p class="mb-0 font-size-11 text-muted">{{ selectedOutlet.IPAddress }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Outlet List -->
                        <div style="max-height: 280px; overflow-y: auto;">
                            <a class="dropdown-item py-2" href="javascript:void(0);"
                               v-for="outlet in filteredOutlets"
                               :key="outlet.OutletID"
                               @click="confirmSwitchOutlet(outlet)">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs mr-2">
                                        <span class="avatar-title rounded-circle"
                                              :class="selectedOutlet && selectedOutlet.OutletID === outlet.OutletID ? 'bg-success text-white' : 'bg-soft-primary text-primary'">
                                            <i class="mdi" :class="selectedOutlet && selectedOutlet.OutletID === outlet.OutletID ? 'mdi-check' : 'mdi-store'"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 font-size-13">{{ outlet.OutletName }}</h6>
                                        <p class="mb-0 font-size-11 text-muted">
                                            {{ outlet.IPAddress || 'No IP' }} • {{ outlet.City }}
                                        </p>
                                    </div>
                                    <span class="badge" :class="outlet.Status === 'Active' ? 'badge-soft-success' : 'badge-soft-danger'">
                                        {{ outlet.Status }}
                                    </span>
                                </div>
                            </a>
                            <div class="text-center py-3 text-muted" v-if="!filteredOutlets.length">
                                <i class="mdi mdi-store-off font-size-20"></i>
                                <p class="mb-0 font-size-12">No outlets found</p>
                            </div>
                        </div>

                        <!-- Footer Info -->
                        <div class="p-2 border-top bg-light">
                            <small class="text-muted d-flex justify-content-between">
                                <span><i class="mdi mdi-information"></i> Total: {{ outlets.length }}</span>
                                <span v-if="isConnected" class="text-success">
                                    <i class="mdi mdi-check-circle"></i> Connected
                                </span>
                            </small>
                        </div>
                    </div>
                </li>

                <!-- Connection Status -->
                <li class="list-inline-item d-none d-md-inline-block" v-if="selectedOutlet">
                    <a class="nav-link waves-effect" href="javascript:void(0);" @click="testConnection" title="Test Connection">
                        <span class="badge" :class="isConnected ? 'badge-success' : 'badge-warning'">
                            <i class="mdi" :class="isConnected ? 'mdi-lan-connect' : 'mdi-lan-disconnect'"></i>
                            {{ isConnected ? 'Connected' : 'Test' }}
                        </span>
                    </a>
                </li>

                <!-- Full Screen -->
                <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
                    <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                        <i class="mdi mdi-fullscreen noti-icon"></i>
                    </a>
                </li>

                <!-- User Profile -->
                <li class="dropdown notification-list list-inline-item">
                    <div class="dropdown notification-list nav-pro-img">
                        <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown"
                           href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img :src="`${mainOrigin}assets/images/users/user-4.jpg`" alt="user" class="rounded-circle"/>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                            <router-link class="dropdown-item" :to="{name : 'Profile'}">
                                <i class="mdi mdi-account-circle m-r-5"></i>Profile
                            </router-link>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" @click="logout" href="#">
                                <i class="mdi mdi-power text-danger"></i>Logout
                            </a>
                        </div>
                    </div>
                </li>
            </ul>

            <ul class="list-inline menu-left mb-0">
                <li class="float-left">
                    <button @click="toggleSidebar" class="button-menu-mobile open-left waves-effect">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    name: 'Navbar',
    data() {
        return {
            outlets: [],
            selectedOutlet: null,
            outletSearch: '',
            isConnected: false,
            testingConnection: false
        }
    },
    computed: {
        filteredOutlets() {
            if (!this.outletSearch) return this.outlets;
            const search = this.outletSearch.toLowerCase();
            return this.outlets.filter(o =>
                o.OutletName.toLowerCase().includes(search) ||
                o.OutletCode?.toLowerCase().includes(search) ||
                o.IPAddress?.toLowerCase().includes(search) ||
                o.City?.toLowerCase().includes(search)
            );
        }
    },
    mounted() {
        this.loadSavedOutlet();
        this.fetchOutlets();
    },
    methods: {
        toggleSidebar() {
            $("body").toggleClass("enlarged");
        },

        logout() {
            localStorage.removeItem("token");
            localStorage.removeItem("selectedOutlet");
            localStorage.removeItem("selected_outlet_id");
            this.$router.push({name: 'Login'});
            this.$toaster.success('Successfully Logged Out');
        },

        // ✅ Fetch active outlets from backend
        fetchOutlets() {
            axios.get('/api/outlet-connection/outlets').then(res => {
                this.outlets = res.data.outlets || [];
            }).catch(err => {
                console.error('Failed to load outlets:', err);
            });
        },

        // ✅ Load saved outlet from localStorage
        loadSavedOutlet() {
            const saved = localStorage.getItem('selectedOutlet');
            if (saved) {
                try {
                    this.selectedOutlet = JSON.parse(saved);
                    this.isConnected = true;
                } catch (e) {
                    console.error('Failed to parse saved outlet:', e);
                    localStorage.removeItem('selectedOutlet');
                }
            }
        },

        // ✅ Confirmation modal before switching outlet
        confirmSwitchOutlet(outlet) {
            // If same outlet selected, do nothing
            if (this.selectedOutlet && this.selectedOutlet.OutletID === outlet.OutletID) {
                this.$toaster.info('Already connected to this outlet');
                return;
            }

            const currentName = this.selectedOutlet ? this.selectedOutlet.OutletName : 'None';

            Swal.fire({
                title: 'Switch Outlet?',
                html: `
                    <div class="text-left p-2">
                        <div class="mb-2">
                            <strong class="text-muted">Current:</strong>
                            <span class="badge badge-secondary ml-1">${currentName}</span>
                        </div>
                        <div class="mb-2">
                            <strong class="text-primary">Switch to:</strong>
                            <span class="badge badge-primary ml-1">${outlet.OutletName}</span>
                        </div>
                        <div class="alert alert-info py-2 mb-0 mt-2">
                            <small>
                                <i class="mdi mdi-information"></i>
                                IP: <code>${outlet.IPAddress}</code> • ${outlet.City || 'N/A'}
                            </small>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#74788d',
                confirmButtonText: '<i class="mdi mdi-check"></i> Yes, Switch',
                cancelButtonText: '<i class="mdi mdi-close"></i> Cancel',
                customClass: {
                    popup: 'animated fadeInDown'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.switchOutlet(outlet);
                }
            });
        },

        // ✅ Switch outlet - call backend to set active outlet
        switchOutlet(outlet) {
            Swal.fire({
                title: 'Connecting...',
                html: `
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p>Switching to <strong>${outlet.OutletName}</strong></p>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });

            axios.post('/api/outlet-connection/set-active', {
                outlet_id: outlet.OutletID
            }).then(res => {
                if (res.data.success) {
                    // Save to localStorage
                    this.selectedOutlet = outlet;
                    localStorage.setItem('selectedOutlet', JSON.stringify(outlet));
                    localStorage.setItem('selected_outlet_id', outlet.OutletID);
                    this.isConnected = true;

                    Swal.fire({
                        icon: 'success',
                        title: 'Connected!',
                        html: `
                            <p>Successfully connected to</p>
                            <h5 class="text-primary mb-2">${outlet.OutletName}</h5>
                            <small class="text-muted">Page will reload to refresh data...</small>
                        `,
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(() => {
                        // Emit event for other components
                        this.$root.$emit('outletChanged', outlet);

                        // Reload page to refresh all data
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Failed',
                        text: res.data.message || 'Failed to connect to outlet database'
                    });
                    this.isConnected = false;
                }
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.response?.data?.message || 'Failed to switch outlet. Please try again.'
                });
                this.isConnected = false;
            });
        },

        // ✅ Test current outlet connection
        testConnection() {
            if (!this.selectedOutlet) {
                this.$toaster.warning('Please select an outlet first');
                return;
            }

            this.testingConnection = true;

            axios.post('/api/outlet-connection/test', {
                outlet_id: this.selectedOutlet.OutletID
            }).then(res => {
                this.testingConnection = false;

                if (res.data.success) {
                    this.isConnected = true;

                    Swal.fire({
                        icon: 'success',
                        title: 'Connection OK!',
                        html: `
                            <p>Successfully connected to</p>
                            <h6 class="text-success">${this.selectedOutlet.OutletName}</h6>
                            <small class="text-muted">Database: ${this.selectedOutlet.DatabaseName || 'N/A'}</small>
                        `,
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                } else {
                    this.isConnected = false;

                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Failed',
                        text: res.data.message || 'Unable to connect to outlet database'
                    });
                }
            }).catch(err => {
                this.testingConnection = false;
                this.isConnected = false;

                this.$toaster.error('Connection test failed: ' + (err.response?.data?.message || 'Unknown error'));
            });
        }
    }
}
</script>

<style scoped>
.avatar-xs {
    height: 1.5rem;
    width: 1.5rem;
}
.avatar-title {
    align-items: center;
    display: flex;
    height: 100%;
    justify-content: center;
    width: 100%;
}
.bg-soft-primary {
    background-color: rgba(85, 110, 230, 0.18) !important;
}
.bg-soft-success {
    background-color: rgba(52, 195, 143, 0.18) !important;
}
.badge-soft-success {
    background-color: rgba(52, 195, 143, 0.18);
    color: #34c38f;
    font-weight: 500;
}
.badge-soft-danger {
    background-color: rgba(244, 106, 106, 0.18);
    color: #f46a6a;
    font-weight: 500;
}
.font-size-11 { font-size: 11px; }
.font-size-12 { font-size: 12px; }
.font-size-13 { font-size: 13px; }
.font-size-20 { font-size: 20px; }

.dropdown-menu-animated {
    animation: dropdownAnimation 0.2s ease;
}

@keyframes dropdownAnimation {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dropdown-item {
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
}

.badge {
    font-size: 11px;
    padding: 4px 8px;
}

/* Custom scrollbar for outlet list */
.dropdown-menu div[style*="overflow-y"] {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

.dropdown-menu div[style*="overflow-y"]::-webkit-scrollbar {
    width: 6px;
}

.dropdown-menu div[style*="overflow-y"]::-webkit-scrollbar-track {
    background: #f7fafc;
}

.dropdown-menu div[style*="overflow-y"]::-webkit-scrollbar-thumb {
    background-color: #cbd5e0;
    border-radius: 3px;
}

.dropdown-menu div[style*="overflow-y"]::-webkit-scrollbar-thumb:hover {
    background-color: #a0aec0;
}

/* Animation classes */
.animated {
    animation-duration: 0.3s;
    animation-fill-mode: both;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translate3d(0, -10px, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

.fadeInDown {
    animation-name: fadeInDown;
}

/* Loading spinner */
.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>
