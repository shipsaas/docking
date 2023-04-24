import { createRouter, createWebHashHistory } from 'vue-router';

export const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('./screens/Home/HomeScreen.vue'),
    },
    {
      path: '/document-templates',
      name: 'document-templates-list',
      component: () =>
        import(
          './screens/DocumentTemplatesList/DocumentTemplatesListScreen.vue'
        ),
    },
    {
      path: '/files',
      name: 'files-list',
      component: () => import('./screens/FilesList/FilesListScreen.vue'),
    },
  ],
});
