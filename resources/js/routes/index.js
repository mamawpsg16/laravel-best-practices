import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes.js';
import { useAuthStore } from '@js/stores/authStore.js';
import NProgress from 'nprogress/nprogress.js';
const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior (to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }else{
      return { top: 0, left:0 }
    }
  }
});

NProgress.configure({ easing: 'ease', speed: 500,  showSpinner: false });

router.beforeEach(async (to, from, next) => {
  NProgress.start();
  const authStore = useAuthStore();
  // Fetch the user data if route requires authentication
  if (to.meta.requiresAuth) {
    await authStore.getUser();
  }

  const isUserAuthenticated = authStore.isUserAuthenticated;
  // Prevent infinite loop for the email-verification route
  if (to.name === 'email-verification') {
    if (isUserAuthenticated && authStore.user && !authStore.user.isVerified) {
      next(); // Allow access to the email-verification route
    } else {
      next({ name: 'dashboard' }); // Redirect to the dashboard if user is verified
    }
    return;
  }

  if (to.meta.requiresAuth && !isUserAuthenticated) {
    // User is not authenticated, redirect to login
    next({ name: 'login' });
  } else if (to.meta.requiresAuth && isUserAuthenticated && authStore.user && !authStore.user.isVerified) {
    // User is authenticated but not verified, redirect to email verification
    next({ name: 'email-verification' });
  } else if (!to.meta.requiresAuth && isUserAuthenticated) {
    // User is authenticated, prevent access to login route
    next({ name: 'dashboard' });
  } else {
    next();
  }
});
router.afterEach((to, from) => {
  NProgress.done();
});

export default router;