import Products from './components/product/Products.vue';
import Create from './components/product/Create.vue';
import Update from './components/product/Update.vue';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Products
    },
    {
        name: 'create',
        path: '/create',
        component: Create
    },
    {
        name: 'update',
        path: '/update',
        component: Update
    },
    {
        name: 'delete',
        path: '/delete',
    }
];
