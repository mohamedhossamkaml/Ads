import Home from './components/Home'
import Dashboard from './components/Dashboard'
import Developer from './components/Developer'
import Profile from './components/Profile'
import Users from './components/Users'
import Adss from './components/ads'
import Category from './components/category'
import Tages from './components/tages'
import NotFound from './components/NotFound'

export default [{
        path: '/home',
        component: Home,
        name: 'home',
    },
    {
        path: '/dashboard',
        component: Dashboard,
        name: 'dashboard',
    },
    {
        path: '/developer',
        component: Developer,
        name: 'developer',
    },
    {
        path: '/profile',
        component: Profile,
        name: 'profile',
    },
    {
        path: '/users',
        component: Users,
        name: 'users',
    },
    {
        path: '/ads',
        component: Adss,
        name: 'ads',
    },
    {
        path: '/category',
        component: Category,
        name: 'category',
    },
    {
        path: '/tages',
        component: Tages,
        name: 'tages',
    },
    {
        path: '*',
        component: NotFound,
        name: 'notfound',
    },

]