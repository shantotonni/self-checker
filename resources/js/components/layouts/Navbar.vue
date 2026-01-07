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
            isConnected: false
        }
    },
    computed: {
        filteredOutlets() {
            if (!this.outletSearch) return this.outlets;
            const search = this.outletSearch.toLowerCase();
            return this.outlets.filter(o =>
                o.OutletName.toLowerCase().includes(search) ||
                o.IPAddress?.toLowerCase().includes(search) ||
                o.City?.toLowerCase().includes(search)
            );
        }
    },
    mounted() {
        this.fetchOutlets();
        this.loadSavedOutlet();
    },
    methods: {
        toggleSidebar() {
            $("body").toggleClass("enlarged");
        },

        logout() {
            localStorage.removeItem("token");
            localStorage.removeItem("selectedOutlet");
            this.$router.push({name: 'Login'});
            this.$toaster.success('Successfully Logged Out');
        },

        fetchOutlets() {
            axios.get('/api/outlets/active').then(res => {
                this.outlets = res.data.outlets || [];
            }).catch(err => {});
        },

        loadSavedOutlet() {
            const saved = localStorage.getItem('selectedOutlet');
            if (saved) {
                this.selectedOutlet = JSON.parse(saved);
                this.isConnected = true;
            }
        },

        // ✅ Confirmation Modal before switching outlet
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
                    <div class="text-left">
                        <p><strong>Current:</strong> ${currentName}</p>
                        <p><strong>Switch to:</strong> ${outlet.OutletName}</p>
                        <p class="text-muted mb-0"><small>IP: ${outlet.IPAddress}</small></p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#74788d',
                confirmButtonText: '<i class="mdi mdi-check"></i> Yes, Switch',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.switchOutlet(outlet);
                }
            });
        },

        // ✅ Switch outlet - call backend to change session
        switchOutlet(outlet) {
            Swal.fire({
                title: 'Connecting...',
                html: `Switching to <strong>${outlet.OutletName}</strong>`,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post('/api/outlet-session/switch', {
                OutletID: outlet.OutletID
            }).then(res => {
                Swal.close();

                if (res.data.success) {
                    // Save to localStorage
                    this.selectedOutlet = outlet;
                    localStorage.setItem('selectedOutlet', JSON.stringify(outlet));
                    this.isConnected = true;

                    Swal.fire({
                        icon: 'success',
                        title: 'Connected!',
                        text: `Now connected to ${outlet.OutletName}`,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Emit event for other components
                    this.$root.$emit('outletChanged', outlet);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Failed',
                        text: res.data.message
                    });
                    this.isConnected = false;
                }
            }).catch(err => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.response?.data?.message || 'Failed to switch outlet'
                });
                this.isConnected = false;
            });
        },

        // ✅ Test current connection
        testConnection() {
            if (!this.selectedOutlet) {
                this.$toaster.warning('Please select an outlet first');
                return;
            }

            axios.post('/api/outlet-session/test').then(res => {
                if (res.data.success) {
                    this.isConnected = true;
                    this.$toaster.success('Connection OK!');
                } else {
                    this.isConnected = false;
                    this.$toaster.error('Connection failed: ' + res.data.message);
                }
            }).catch(err => {
                this.isConnected = false;
                this.$toaster.error('Connection test failed');
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
}
.badge-soft-danger {
    background-color: rgba(244, 106, 106, 0.18);
    color: #f46a6a;
}
.font-size-11 { font-size: 11px; }
.font-size-12 { font-size: 12px; }
.font-size-13 { font-size: 13px; }
.font-size-20 { font-size: 20px; }
.dropdown-menu-animated {
    animation: dropdownAnimation 0.2s ease;
}
@keyframes dropdownAnimation {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
.dropdown-item:hover {
    background-color: #f8f9fa;
}
</style>
