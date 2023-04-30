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
    <p class="mt-4 text-sm text-gray-500">Are you sure you want to delete this template? This template will be marked as inactive and still available in the database.</p>
    <template #bottom-buttons>
      <div class="flex gap-1">
        <Button type="secondary" @click="isOpenModal = false" :disabled="isLoading">Cancel</Button>
        <Button type="error" @click="onClickConfirmDelete" :disabled="isLoading">Delete</Button>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import Button from '../../../components/Button/Button.vue';
import { documentTemplateRepository } from '../../../repositories/documentTemplate.repository';
import { notify } from '@kyvg/vue3-notification';
import { useLoading } from '../../../composable/useLoading';
import Modal from "../../../components/Modal/Modal.vue";
import {ref} from "vue";

const props = defineProps({
  template: {
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

  const data = await documentTemplateRepository.destroy(props.template.uuid);

  stopLoading();

  if (!data) {
    return;
  }

  notify({
    type: 'success',
    title: 'Action OK',
    text: 'Document Template has been deleted.',
  });

  emits('deleted');

  isOpenModal.value = false;
};
</script>

<style scoped></style>
