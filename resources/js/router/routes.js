//Route List

const routes = [
  {
    path: '/',
    name: 'home',
    redirect: '/dash'
  },
  {
    path: '/dash',
    name: 'dashmin',
    component: () => import('../Pages/Home.vue'),
  },
  {
    path: '/posts',
    name: 'posts',
    component: () => import('../Pages/PostList.vue'),
    meta: {
      type: 1,
    }
  },
  {
    path: '/posts/:id',
    name: 'posts.edit',
    component: () => import('../Pages/PostEdit.vue'),
    meta: {
      type: 1,
    }
  },
  {
    path: '/categories',
    name: 'categories',
    component: () => import('../Pages/PostList.vue'),
    meta: {
      type: 2,
    }
  },
  {
    path: '/tags',
    name: 'tags',
    component: () => import('../Pages/PostList.vue'),
    meta: {
      type: 3,
    }
  },
  {
    path: '/pages',
    name: 'pages',
    component: () => import('../Pages/PostList.vue'),
    meta: {
      type: 4,
    }
  },
  { path: '/:pathMatch(.*)*', name: 'not-found', redirect: '/dash' },
]

export default routes;