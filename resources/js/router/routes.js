//Route List

const routes = [
  {
    path: '/',
    name: 'home',
    redirect: '/dash',
  },
  {
    path: '/dash',
    name : 'dashboard',
    component: DashBoard,
  },
  { path: '/:pathMatch(.*)*', name: 'not-found', redirect: '/dash' },
]

export default routes;