import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);
const base_url = '/self-checker';

const checkToken = (to, from, next) => {
    let token = localStorage.getItem('token');
    if (token === undefined || token === null || token === '') {
        next(base_url + '/login');
    } else {
        next();
    }
};

const activeToken = (to, from, next) => {
    let token = localStorage.getItem('token');
    if (token === undefined || token === null || token === '') {
        next();
    } else {
        next(base_url + '/');
    }
};

import Login from "../views/auth/Login";
//main route
import Main from "../components/layouts/Main";

import Dasboard from '../views/dashboard/Index'
import UserList from '../views/user/List'
import MenuPermission from '../views/user/MenuPermission'
import MenuList from '../views/menu/List'
import MenuCreate from '../views/menu/Create'
import MenuEdit from '../views/menu/Edit'
import ChangePassword from '../views/settings/ChangePassword'
import RoleList from '../views/role/List'
import OutletList from '../views/outlet/List'
import MobileTerminal from '../views/setup/MobileTerminal'
import PaymentREQInfoLog from '../views/setup/PaymentREQInfoLog'
import PaymentSuccessInfo from '../views/setup/PaymentSuccessInfo'
import PaymentType from '../views/setup/PaymentType'
import AppVersionInfo from '../views/setup/AppVersionInfo'

import Profile from '../views/profile/Profile'

const routes = [
    {
        path: base_url + '/',
        component: Main,
        redirect: {name:'Dashboard'},
        children: [
            {path: base_url + '/dashboard', name: 'Dashboard', component: Dasboard},
            //user vue route
            {path: base_url + '/user-list', name: 'UserList', component: UserList},
            {path: base_url + '/user-menu-permission', name: 'UserMenuPermission', component: MenuPermission},
            //menu vue route
            {path: base_url + '/menu-list', name: 'MenuList', component: MenuList},
            {path: base_url + '/menu-create', name: 'MenuCreate', component: MenuCreate},
            {path: base_url + '/menu-edit/:MenuId', name: 'MenuEdit', component: MenuEdit},
            {path: base_url + '/change-password', name: 'ChangePassword', component: ChangePassword},
            //Role vue route
            {path: base_url + '/role-list', name: 'RoleList', component: RoleList},
            {path: base_url + '/outlet-list', name: 'OutletList', component: OutletList},
            {path: base_url + '/mobile-terminal', name: 'MobileTerminal', component: MobileTerminal},
            {path: base_url + '/payment-req-info-log', name: 'PaymentREQInfoLog', component: PaymentREQInfoLog},
            {path: base_url + '/payment-success-info', name: 'PaymentSuccessInfo', component: PaymentSuccessInfo},
            {path: base_url + '/payment-type', name: 'PaymentType', component: PaymentType},
            {path: base_url + '/app-version-info', name: 'AppVersionInfo', component: AppVersionInfo},

            //user profile route
            {
                path: base_url + '/profile', name: 'Profile', component: Profile
            },

        ],
        beforeEnter(to, from, next) {
            checkToken(to, from, next);
        }
    },
    {
        path: base_url + '/login',
        name: 'Login',
        component: Login,
        beforeEnter(to, from, next) {
            activeToken(to, from, next);
        }
    },
]

const router = new VueRouter({
    mode: 'history',
    routes
});

router.afterEach(() => {
    $('#preloader').hide();
});

export default router
