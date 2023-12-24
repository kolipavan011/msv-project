//Route List

const routes = [
  {
    path: '/',
    name: 'home',
    redirect: '/dash'
  },
  {
    path: '/dash',
    name: 'dash',
    component: () => import('../Pages/Home.vue'),
  },
  {
    path: '/posts',
    name: 'post-list',
    component: () => import('../Pages/PostList.vue'),
  },
  { path: '/:pathMatch(.*)*', name: 'not-found', redirect: '/dash' },
]

export default routes;