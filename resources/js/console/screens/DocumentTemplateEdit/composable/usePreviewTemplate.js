import { ref, h } from 'vue';
import Dropdown from '../../../components/Dropdown/Dropdown.vue';
import PreviewHtmlModal from '../components/PreviewHtmlModal.vue';
import { documentTemplateRepository } from '../../../repositories/documentTemplate.repository.js';
import printJS from 'print-js';

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
  const renderedHtml = ref('');

  const previewHtml = async (wantsHtmlString) => {
    const data = await documentTemplateRepository.previewHtml(
      template.value.uuid
    );
    if (!data) {
      return;
    }

    if (wantsHtmlString) {
      return data.html;
    }

    renderedHtml.value = data.html;
  };

  const previewPrinting = async () => {
    const html = await previewHtml(true);
    if (!html) {
      return;
    }

    printJS({
      type: 'raw-html',
      printable: html,
      documentTitle: 'DocKing Preview PDF Template',
    });
  };

  const previewPdf = async () => {
    const data = await documentTemplateRepository.previewPdf(
      template.value.uuid
    );
    if (!data) {
      return;
    }

    printJS({
      type: 'pdf',
      printable: data.url,
      documentTitle: 'DocKing Preview PDF Template',
    });
  };

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
    renderedHtml,
  };
};
