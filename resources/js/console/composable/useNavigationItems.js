import { onMounted, ref } from 'vue';
import {
  DocumentDuplicateIcon,
  HomeIcon,
  DocumentPlusIcon,
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
];

export const useNavigationItems = () => {
  const navigationItems = ref(NAVIGATION_ITEMS);

  const setNavigationItemActive = (navigationItem) => {
    navigationItems.value.forEach(
      (item) => (item.current = item.name === navigationItem.name)
    );
  };

  onMounted(() => {
    if (location.hash === '#/') {
      setNavigationItemActive(navigationItems.value[DEFAULT_NAVIGATION_INDEX]);
      return;
    }

    const fulfilableItem = navigationItems.value.find((item, index) => {
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
