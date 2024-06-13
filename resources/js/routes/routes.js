const Login = () => import('@js/views/authentication/Login.vue')
const Register = () => import('@js/views/authentication/Register.vue')

const NotFound = () => import('@js/views/errors/NotFound.vue')
const Forbidden = () => import('@js/views/errors/Forbidden.vue')
const Dashboard = () => import('@js/views/dashboard/Index.vue')
const About = () => import('@js/views/example/About.vue')
const EmailVerification = () => import('@js/views/authentication/EmailVerification.vue')
const ResetPasswordConfirmation = () => import('@js/views/authentication/ResetPasswordConfirmation.vue')

/** ADMIN */
const Monitoring = () => import('@js/views/admin/user/Index.vue')

/** USER */
const TaskIndex = () => import('@js/views/user/task/Index.vue')
const ReportCreate = () => import('@js/views/user/report/Create.vue')
const ReportMonitoring = () => import('@js/views/user/report/Index.vue')
const ReportDetails = () => import('@js/views/user/report/Details.vue')

const routes = [
    /** CATCH NOT DEFINED ROUTE */
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound, meta: { requiresAuth: true } },
    
    
    /** UNAUTHENTICATED ROUTES */
    { path: '/login', component: Login,  name:'login', meta: { requiresAuth: false } },
    { path: '/register', component: Register,  name:'register', meta: { requiresAuth: false } },
    { path: '/reset-password/:token', component: ResetPasswordConfirmation,  name:'reset-password-confirmation', meta: { requiresAuth: false } },

     // Authenticated routes
    { path: '/', component: Dashboard, name: 'dashboard', meta: { requiresAuth: true, breadcrumb: 'Dashboard' } },
    { path: '/forbidden', component: Forbidden, name: 'forbidden', meta: { requiresAuth: true, breadcrumb: 'Forbidden' } },
    { path: '/about', component: About, name: 'about', meta: { requiresAuth: true, breadcrumb: 'About' } },
    { path: '/email-verification', component: EmailVerification, name: 'email-verification', meta: { requiresAuth: true, breadcrumb: 'Email Verification' } },

    // USER routes
    { path: '/tasks', component: TaskIndex, name: 'task-index', meta: { requiresAuth: true, breadcrumb: 'Tasks' } },

    // ADMIN routes
    {
      path: '/admin',
      children: [
        {
          path: 'users',
          component: Monitoring,
          name: 'admin-user',
          meta: { requiresAuth: true, breadcrumb: 'Users Monitoring' }
        },
      ],
    },
    {
      path: '/report',
      meta: { breadcrumb: 'Report' },
      children: [
        {
          path: '',
          component: ReportMonitoring,
          name: 'report',
          meta: { requiresAuth: true, breadcrumb: 'Monitoring' }
        },
        {
           path: 'create',
           component: ReportCreate,
           name: 'report-create',
           meta: { requiresAuth: true, breadcrumb: 'Create Report' }
        },
        {
          path: ':uuid',
          component: ReportDetails,
          name: 'report-details',
          meta: { requiresAuth: true, breadcrumb: 'Report Details' }
        },
      ],
    },
  ];

export default routes;