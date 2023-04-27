import { ref } from 'vue';
import {
  DocumentDuplicateIcon,
  HomeIcon,
  DocumentPlusIcon,
} from '@heroicons/vue/24/outline';

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
];

export const useNavigationItems = () => {
  const navigationItems = ref(NAVIGATION_ITEMS);

  const setNavigationItemActive = (navigationItem) => {
    navigationItems.value.forEach(
      (item) => (item.current = item.name === navigationItem.name)
    );
  };

  return {
    navigationItems,
    setNavigationItemActive,
  };
};