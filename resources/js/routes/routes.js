const Login = () => import('@js/views/authentication/Login.vue')
const Register = () => import('@js/views/authentication/Register.vue')

const NotFound = () => import('@js/views/errors/NotFound.vue')
const Forbidden = () => import('@js/views/errors/Forbidden.vue')
const Dashboard = () => import('@js/views/dashboard/Index.vue')
const About = () => import('@js/views/example/About.vue')
const User = () => import('@js/views/user/Index.vue')
const Tasks = () => import('@js/views/task/Index.vue')

const routes = [
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound, meta: { requiresAuth: true } },
    // OR
    // { path: '/:pathMatch(.*)*', name: 'NotFound', component: () => import('./views/UserDetails.vue') , meta: { requiresAuth: true }  },
    
    /** UNAUTHENTICATED ROUTES */
    { path: '/login', component: Login,  name:'login', meta: { requiresAuth: false } },
    { path: '/register', component: Register,  name:'register', meta: { requiresAuth: false } },
    
    /** AUTHENTICATED ROUTES */
    { path: '/', component: Dashboard, name:'dashboard',meta: { requiresAuth: true } },
    { path: '/forbidden', component: Forbidden, name:'forbidden',meta: { requiresAuth: true } },
    { path: '/about', component: About, name:'about', meta: { requiresAuth: true } },
    { path: '/users/:username', component: User,  name:'user', meta: { requiresAuth: true } },
    { path: '/tasks', component: Tasks,  name:'tasks', meta: { requiresAuth: true } },
  ];

export default routes;