const NotFound = () => import('@js/views/errors/NotFound.vue')
const Dashboard = () => import('@js/views/dashboard/Index.vue')
const About = () => import('@js/views/example/About.vue')
const Login = () => import('@js/views/authentication/Login.vue')
const User = () => import('@js/views/user/Index.vue')

const routes = [
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound, meta: { requiresAuth: true } },
    // OR
    // { path: '/:pathMatch(.*)*', name: 'NotFound', component: () => import('./views/UserDetails.vue') , meta: { requiresAuth: true }  },
    
    { path: '/', component: Dashboard, name:'dashboard',meta: { requiresAuth: true } },
    { path: '/about', component: About, name:'about', meta: { requiresAuth: true } },
    { path: '/login', component: Login,  name:'login', meta: { requiresAuth: false } },
    { path: '/users/:username', component: User,  name:'user', meta: { requiresAuth: true } },
  ];

export default routes;