import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes.js';
import { useAuthStore } from '@js/stores/authStore.js';

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

const isAuthenticated = () => {
  return localStorage.getItem('authenticated') === 'true' || useAuthStore().isAuthenticated;
};


router.beforeEach(async (to, from, next) => {
  if (to.meta.requiresAuth) {
      await useAuthStore().getUser();
  }

  const isUserAuthenticated = isAuthenticated();
  if (to.meta.requiresAuth && !isUserAuthenticated) {
    // User is not authenticated, redirect to login
    next({ name: 'login' })
  } else if (!to.meta.requiresAuth && isUserAuthenticated) {
    // User is authenticated, prevent access to login route
    next({ name: 'dashboard' })
  } else {
    next()
  }
})


export default router;