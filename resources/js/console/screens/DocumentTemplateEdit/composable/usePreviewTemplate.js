import { h } from 'vue';
import Dropdown from '../../../components/Dropdown/Dropdown.vue';

const previewModes = [
  {
    key: 'html',
    label: 'Preview HTML',
  },
  {
    key: 'printing',
    label: 'Preview Printing (Browser)',
  },
  {
    key: 'pdf',
    label: 'Preview PDF',
  },
];

/**
 * @param {Ref<{uuid: String | null}>} template
 */
export const usePreviewTemplate = (template) => {
  const previewHtml = () => {};
  const previewPrinting = () => {};
  const previewPdf = () => {};

  const onPreview = (mode) => {
    switch (mode) {
      case 'html':
        return previewHtml();
      case 'printing':
        return previewPrinting();
      case 'pdf':
        return previewPdf();
    }
  };

  return {
    Dropdown: h(Dropdown, {
      label: 'Preview',
      items: previewModes,
      onSelected: onPreview,
    }),
  };
};
