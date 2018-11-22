import Contracts from './components/contracts/contracts.vue';
import ContractForm from './components/contracts/form.vue'

import Products from './components/products/products.vue';
import ProductForm from './components/products/form.vue'

import Providers from './components/providers/providers.vue';
import ProviderForm from './components/providers/form.vue'

import Shops from './components/shops/shops.vue';
import ShopForm from './components/shops/form.vue'

import Users from  './components/users/users.vue';
import UserForm from './components/users/form.vue'

import Login from './components/auth/sign_in.vue';
import Logout from './components/auth/logout.vue';


import store from './store/modules/auth.js';

const ifNotAuthenticated = (to, from, next) => {
    const isAuth = localStorage.getItem('token') || '';
    if (! isAuth) {
        next()
        return
    }
    next('/contracts')
};

const ifAuthenticated = (to, from, next) => {
    const isAuth = localStorage.getItem('token') || '';
    if (isAuth) {
        next()
        return
    }
    next('/login')
};

export const routes = [
    {
        path: '/users',
        component: Users,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/user/add',
        component: UserForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/user/edit/:id',
        component: UserForm,
        beforeEnter: ifNotAuthenticated
    },
    {
        path: '/shops',
        component: Shops,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/shop/add',
        component: ShopForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/shop/edit/:id',
        component: ShopForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/providers',
        component: Providers,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/provider/add',
        component: ProviderForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/provider/edit/:id',
        component: ProviderForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/products',
        component: Products,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/product/add',
        component: ProductForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/product/edit/:id',
        component: ProductForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/contracts',
        component: Contracts,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/contract/add',
        component: ContractForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/contract/edit/:id',
        component: ContractForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/login',
        component: Login,
        beforeEnter: ifNotAuthenticated
    },
    {
        path: '/logout',
        component: Logout,
        beforeEnter: ifAuthenticated
    }
];