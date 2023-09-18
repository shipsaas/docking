<template>
  <Button
    type="error"
    @click="onClickDelete"
    :disabled="isLoading"
  >
    Delete
  </Button>
  <Modal
    :is-open="isOpenModal"
    title="Delete Confirmation"
  >
    <p class="mt-4 text-sm text-gray-500">
      Are you sure you want to delete this Language? You cannot manage and use
      the translations of this language anymore.
    </p>
    <template #bottom-buttons>
      <div class="flex gap-1">
        <Button
          type="secondary"
          @click="isOpenModal = false"
          :disabled="isLoading"
        >
          Cancel
        </Button>
        <Button
          type="error"
          @click="onClickConfirmDelete"
          :disabled="isLoading"
        >
          Delete
        </Button>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import Button from '../../../components/Button/Button.vue';
import { notify } from '@kyvg/vue3-notification';
import { useLoading } from '../../../composable/useLoading';
import Modal from '../../../components/Modal/Modal.vue';
import { ref } from 'vue';
import { languageRepository } from '../../../repositories/language.repository';

const props = defineProps({
  language: {
    type: Object,
    required: true,
  },
});

const emits = defineEmits(['deleted']);

const { isLoading, startLoading, stopLoading } = useLoading();
const isOpenModal = ref(false);

const onClickDelete = () => {
  isOpenModal.value = true;
};

const onClickConfirmDelete = async () => {
  startLoading();

  const data = await languageRepository.destroy(props.language.uuid);

  stopLoading();

  if (!data) {
    return;
  }

  notify({
    type: 'success',
    title: 'Action OK',
    text: 'Language has been deleted.',
  });

  emits('deleted');

  isOpenModal.value = false;
};
</script>
