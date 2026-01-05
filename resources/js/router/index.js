import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);
const base_url = '/price-survey';

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
import CustomerList from '../views/customer/List'
import RoleList from '../views/role/List'
import Profile from '../views/profile/Profile'
import ShowroomList from '../views/showroom/List'
import DealerList from '../views/dealer/List'
import BusinessWiseProduct from '../views/price-survey/BusinessWiseProduct'
import MokamList from '../views/price-survey/MokamList'
import ProductPriceList from '../views/price-survey/ProductPriceList'
import SurveyChart from '../views/price-survey/SurveyChart'


const routes = [
    {
        path: base_url + '/',
        component: Main,
        redirect: {name:'Dashboard'},
        children: [
            {
                path: base_url + '/dashboard', name: 'Dashboard', component: Dasboard
            },
            //user vue route
            {
                path: base_url + '/user-list', name: 'UserList', component: UserList
            },
            {
                path: base_url + '/user-menu-permission', name: 'UserMenuPermission', component: MenuPermission
            },
            //menu vue route
            {
                path: base_url + '/menu-list', name: 'MenuList', component: MenuList
            },
            {
                path: base_url + '/menu-create', name: 'MenuCreate', component: MenuCreate
            },
            {
                path: base_url + '/menu-edit/:MenuId', name: 'MenuEdit', component: MenuEdit
            },
            {
                path: base_url + '/change-password', name: 'ChangePassword', component: ChangePassword
            },
            //Role vue route
            {
                path: base_url + '/role-list', name: 'RoleList', component: RoleList
            },

            //user profile route
            {
                path: base_url + '/profile', name: 'Profile', component: Profile
            },
            //customer vue route
            {
                path: base_url + '/customer-list', name: 'CustomerList', component: CustomerList
            },

            //Showroom
            {
                path: base_url + '/showroom-list', name: 'ShowroomList', component: ShowroomList
            },
            //dealer list
            {
                    path: base_url + '/dealer-list', name: 'DealerList', component: DealerList
            },
            //business-wise-product list
            {path: base_url + '/business-wise-product', name: 'BusinessWiseProduct', component: BusinessWiseProduct},
            {path: base_url + '/mokam-list', name: 'MokamList', component: MokamList},
            {path: base_url + '/product-price-list', name: 'ProductPriceList', component: ProductPriceList},
            {path: base_url + '/survey-chart', name: 'SurveyChart', component: SurveyChart},

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
