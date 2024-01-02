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
    meta: {
      title: 'Dashmin Overview',
    }
  },
  {
    path: '/posts',
    name: 'posts',
    component: () => import('../Pages/PostList.vue'),
    meta: {
      type: 1,
      title: 'Posts Overview',
      route:'posts.edit'
    }
  },
  {
    path: '/posts/:id',
    name: 'posts.edit',
    component: () => import('../Pages/PostEdit.vue'),
    meta: {
      type: 1,
      title: 'Post Edit',
    }
  },
  {
    path: '/categories',
    name: 'categories',
    component: () => import('../Pages/PostList.vue'),
    meta: {
      type: 2,
      title: 'Categories Overview',
      route:'categories.edit'
    }
  },
  {
    path: '/categories/:id',
    name: 'categories.edit',
    component: () => import('../Pages/PostEdit.vue'),
    meta: {
      type: 2,
      title: 'Category Edit',
    }
  },
  {
    path: '/tags',
    name: 'tags',
    component: () => import('../Pages/PostList.vue'),
    meta: {
      type: 3,
      title: 'Tags Overview',
      route: 'tags.edit',
    }
  },
  {
    path: '/tags/:id',
    name: 'tags.edit',
    component: () => import('../Pages/PostEdit.vue'),
    meta: {
      type: 3,
      title: 'Tags Edit',
    }
  },
  {
    path: '/pages',
    name: 'pages',
    component: () => import('../Pages/PostList.vue'),
    meta: {
      type: 4,
      title: 'Pages Overview',
      route: 'pages.edit',
    }
  },
  {
    path: '/pages/:id',
    name: 'pages.edit',
    component: () => import('../Pages/PostEdit.vue'),
    meta: {
      type: 4,
      title: 'Pages Edit',
    }
  },
  {
    path: '/videos',
    name: 'videos',
    component: () => import('../Pages/Videos.vue'),
    meta: {
      title: 'Videos',
    }
  },
  {
    path: '/vidbot',
    name: 'vidbot',
    component: () => import('../Pages/Vidbot.vue'),
    meta: {
      title: 'Vidbot',
    }
  },
  { path: '/:pathMatch(.*)*', name: 'not-found', redirect: '/dash' },
]

export default routes;