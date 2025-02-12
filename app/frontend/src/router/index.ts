import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'Home',
    component: () => import('@/components/Home.vue'),
  },
  {
    path: '/shop',
    name: 'Shop',
    component: () => import('@/components/shop/Shop.vue'),
  },
  {
    path: '/about',
    name: 'About',
    component: () => import('@/components/about/About.vue')
  },
  {
    path: '/services',
    name: 'Services',
    component: () => import('@/components/servic/Services.vue')
  },
  {
    path: '/blog',
    name: 'Blog',
    component: () => import('@/components/blog/Blog.vue')
  },
  {
    path: '/contact_us',
    name: 'Contact Us',
    component: () => import('@/components/contact/ContactUs.vue')
  },
  {
    path: '/cart',
    name: 'Cart',
    component: () => import('@/components/cart/Cart.vue')
  },
  {
    path: '/checkout',
    name: 'Checkout',
    component: () => import('@/components/checkout/Checkout.vue')
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('@/components/user/Register.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/components/user/Login.vue')
  },
  {
    path: '/thankyou',
    name: 'Thank You',
    component: () => import('@/components/notification/Thankyou.vue')
  },





  {
    path: '/admin/register',
    name: 'Register Admin',
    component: () => import('@/admin/AdminSignup.vue')
  },
  {
    path: '/admin',
    name: 'Admin Dashboard',
    component: () => import('@/admin/Index.vue')
  },
  {
    path: '/admin/product',
    name: 'Admin Docs',
    component: () => import('@/admin/Docs.vue')
  },
  {
    path: '/admin/account',
    name: 'Admin Account',
    component: () => import('@/admin/Account.vue')
  },
  {
    path: '/admin/notification',
    name: 'Admin Notification',
    component: () => import('@/admin/Notifications.vue')
  },
  {
    path: '/admin/settings',
    name: 'Admin Settings',
    component: () => import('@/admin/Settings.vue')
  },
  {
    path: '/admin/orders',
    name: 'Admin Orders',
    component: () => import('@/admin/Orders.vue')
  },
  {
    path: '/admin/charts',
    name: 'Admin Charts',
    component: () => import('@/admin/Chart.vue')
  },
  {
    path: '/admin/login',
    name: 'Admin Login',
    component: () => import('@/admin/Login.vue')
  },
  {
    path: '/admin/reset_password',
    name: 'Admin Reset Password',
    component: () => import('@/admin/ResetPassword.vue')
  },
  {
    path: '/admin/404',
    name: 'Admin Not Found',
    component: () => import('@/admin/404.vue')
  },
  {
    path: '/admin/help',
    name: 'Admin Help Page',
    component: () => import('@/admin/Help.vue')
  },
  {
    path: '/admin/edit_product/:id',
    name: 'Edit Product Page',
    component: () => import('@/admin/Editproduct.vue')
  },
  {
    path: '/admin/about_us_settings',
    name: 'Edit About Us Page',
    component: () => import('@/admin/AboutUsSetUp.vue')
  }

];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL), 
  routes, 
});

// global navigation guard
//---------------------------------------------------
router.beforeEach((to, from, next) => {
  // Check if the user is logged in
  const user = localStorage.getItem('user');
  const admin = localStorage.getItem('admin');
  
  if(to.path === '/admin' && !admin || to.path === '/admin/' && !admin){
    next('/admin/login');
  }if(to.path === '/admin' && admin){
    next();
  }


  if (to.path === '/login' && user) {
    next('/');
  }if (to.path === '/register' && user) {
    next('/');
  }if (to.path === '/cart' && !user) {
    next('/');
  }if (to.path === '/checkout' && user) {
    next('/');
  }
   else {
    next();
  }
});


export default router;
