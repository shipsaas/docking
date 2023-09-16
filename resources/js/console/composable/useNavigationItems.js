import { onMounted, ref } from 'vue';
import {
  DocumentDuplicateIcon,
  HomeIcon,
  DocumentPlusIcon,
  AtSymbolIcon,
  LanguageIcon,
  DocumentTextIcon,
  FolderIcon,
} from '@heroicons/vue/24/outline';

const DEFAULT_NAVIGATION_INDEX = 0;
const NAVIGATION_ITEMS = [
  { name: 'Dashboard', href: '#', icon: HomeIcon, current: true },
  {
    name: 'Document Templates',
    href: '#/document-templates',
    icon: DocumentPlusIcon,
    current: false,
  },
  {
    name: 'Files',
    href: '#/files',
    icon: DocumentDuplicateIcon,
    current: false,
  },
  {
    name: 'Fonts',
    href: '#/fonts',
    icon: AtSymbolIcon,
    current: false,
  },
  {
    name: 'Localization',
    href: '#/localizations',
    current: false,
    children: [
      {
        name: 'Languages',
        href: '#/languages',
        icon: LanguageIcon,
        current: false,
      },
      {
        name: 'Translation Groups',
        href: '#/translation-groups',
        icon: FolderIcon,
        current: false,
      },
      {
        name: 'Translations',
        href: '#/translations',
        icon: DocumentTextIcon,
        current: false,
      },
    ],
  },
];

export const useNavigationItems = () => {
  const navigationItems = ref(
    NAVIGATION_ITEMS.map((item) => {
      item.children = item.children
        ? item.children.map((child) => ({ ...child, parent: item }))
        : undefined;

      return item;
    })
  );

  const setNavigationItemActive = (selectedItem) => {
    navigationItems.value
      .flatMap((item) => (item.children ? [item, ...item.children] : item))
      .forEach((item) => (item.current = false));

    if (selectedItem.parent) {
      selectedItem.parent.current = true;
    }

    selectedItem.current = true;
  };

  onMounted(() => {
    if (location.hash === '#/') {
      setNavigationItemActive(navigationItems.value[DEFAULT_NAVIGATION_INDEX]);
      return;
    }

    const fulfilableItem = navigationItems.value
      .flatMap((item) => (item.children ? [item, ...item.children] : item))
      .find((item, index) => {
        return (
          index !== DEFAULT_NAVIGATION_INDEX &&
          location.hash.startsWith(item.href)
        );
      });

    fulfilableItem && setNavigationItemActive(fulfilableItem);
  });

  return {
    navigationItems,
    setNavigationItemActive,
  };
};
