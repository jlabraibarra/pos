import { createRouter, createWebHistory } from 'vue-router';
import Sales from './components/pos/Sales.vue';
import Products from './components/pos/Products.vue';
    import AddProduct from './components/pos/components/Product.vue';
import Inventory from './components/pos/Inventory.vue';

const routes = [
  { path: '/sales', name: 'Sales', component: Sales },
  { path: '/products', name: 'Products', component: Products },
    { path: '/products/add', name: 'add-product', component: AddProduct },
    { path: '/products/edit', name: 'edit-product', component: AddProduct },
  { path: '/inventory', name: 'Inventory', component: Inventory },
];

const router = createRouter({ history: createWebHistory(), routes });

export default router;
