<template>
  <Button
    @click="onClickDuplicate"
    :disabled="isLoading"
  >
    Duplicate
  </Button>
  <Modal
    :is-open="isOpenModal"
    title="Duplicate Template"
  >
    <p class="mt-4 text-sm text-gray-500">
      In order to duplicate the template, you need to provide the unique
      identifier key for the new template
    </p>
    <div class="my-2">
      <Input
        v-model="uniqueIdentifierKey"
        label="Unique Identifier Key"
      />
    </div>
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
          @click="onClickConfirmDuplicate"
          :disabled="isLoading"
        >
          Duplicate
        </Button>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import Button from '../../../components/Button/Button.vue';
import { documentTemplateRepository } from '../../../repositories/documentTemplate.repository';
import { notify } from '@kyvg/vue3-notification';
import { useLoading } from '../../../composable/useLoading';
import Modal from '../../../components/Modal/Modal.vue';
import { ref } from 'vue';
import Input from '../../../components/Input/Input.vue';

const props = defineProps({
  template: {
    type: Object,
    required: true,
  },
});

const emits = defineEmits(['duplicated']);

const { isLoading, startLoading, stopLoading } = useLoading();
const isOpenModal = ref(false);
const uniqueIdentifierKey = ref('');

const onClickDuplicate = () => {
  isOpenModal.value = true;
};

const onClickConfirmDuplicate = async () => {
  startLoading();

  const data = await documentTemplateRepository.duplicate(props.template.uuid, {
    key: uniqueIdentifierKey.value,
  });

  stopLoading();

  if (!data) {
    return;
  }

  notify({
    type: 'success',
    title: 'Action OK',
    text: 'Document Template has been duplicated.',
  });

  emits('duplicated', {
    template: {
      uuid: data.uuid,
    },
  });

  isOpenModal.value = false;
};
</script>

<style scoped></style>
