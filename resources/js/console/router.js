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
      path: '/document-templates/:uuid',
      name: 'document-template-edit',
      component: () =>
        import('./screens/DocumentTemplateEdit/DocumentTemplateEdit.vue'),
      props: (route) => ({ uuid: route.params.uuid }),
    },
    {
      path: '/files',
      name: 'files-list',
      component: () => import('./screens/FilesList/FilesListScreen.vue'),
    },
    {
      path: '/fonts',
      name: 'fonts-list',
      component: () => import('./screens/FontsList/FontsListScreen.vue'),
    },
    {
      path: '/languages',
      name: 'languages-list',
      component: () =>
        import('./screens/LanguagesList/LanguaguesListScreen.vue'),
    },
    {
      path: '/translations',
      name: 'translations-list',
      component: () =>
        import('./screens/TranslationsList/TranslationsListScreen.vue'),
    },
  ],
});
