const Login = () => import('@js/views/authentication/Login.vue')
const Register = () => import('@js/views/authentication/Register.vue')

const NotFound = () => import('@js/views/errors/NotFound.vue')
const Forbidden = () => import('@js/views/errors/Forbidden.vue')
const Dashboard = () => import('@js/views/dashboard/Index.vue')
const About = () => import('@js/views/example/About.vue')
const EmailVerification = () => import('@js/views/authentication/EmailVerification.vue')
const ResetPasswordConfirmation = () => import('@js/views/authentication/ResetPasswordConfirmation.vue')

/** ADMIN */
const AdminUserMonitoring = () => import('@js/views/admin/user/Index.vue')
const AdminReportMonitoring = () => import('@js/views/admin/report/Index.vue')

/** USER */
const UserTaskIndex = () => import('@js/views/user/task/Index.vue')
const UserReportCreate = () => import('@js/views/user/report/Create.vue')

const routes = [
    /** CATCH NOT DEFINED ROUTE */
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound, meta: { requiresAuth: true } },
    
    
    /** UNAUTHENTICATED ROUTES */
    { path: '/login', component: Login,  name:'login', meta: { requiresAuth: false } },
    { path: '/register', component: Register,  name:'register', meta: { requiresAuth: false } },
    { path: '/reset-password/:token', component: ResetPasswordConfirmation,  name:'reset-password-confirmation', meta: { requiresAuth: false } },

    /** AUTHENTICATED ROUTES */
    { path: '/', component: Dashboard, name:'dashboard',meta: { requiresAuth: true } },
    { path: '/forbidden', component: Forbidden, name:'forbidden',meta: { requiresAuth: true } },
    { path: '/about', component: About, name:'about', meta: { requiresAuth: true } },
    { path: '/email-verification', component: EmailVerification,  name:'email-verification', meta: { requiresAuth: true } },

    /** USER */
    { path: '/tasks', component: UserTaskIndex,  name:'task-index', meta: { requiresAuth: true } },
    { path: '/report/create', component: UserReportCreate, name:'report-create', meta: { requiresAuth: true } },

    /** ADMIN */
    {
      path: '/admin',
      children: [
        {
          path: 'users',
          component: AdminUserMonitoring,
          name:'admin-user',
          meta: { requiresAuth: true }
        },
        { 
          path: '/report',
          children: [
            {
              path: '',
              component: AdminReportMonitoring,
              name: 'admin-report',
              meta: { requiresAuth: true } 
            },
          ],
        }
      ],
    },

  ];

export default routes;