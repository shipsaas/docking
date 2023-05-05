<template>
  <Button @click="onClickCreate">Create New Template</Button>
  <Modal
    title="Create New Document Template"
    :is-open="isOpenModal"
  >
    <template #default>
      <div class="flex flex-col mt-4 gap-2">
        <Input
          v-model="formFields.key"
          label="Unique Identifier Key"
          :disabled="isLoading"
        />
        <Input
          v-model="formFields.category"
          label="Category"
          :disabled="isLoading"
        />
        <Input
          v-model="formFields.title"
          label="Title"
          :disabled="isLoading"
        />
      </div>
    </template>
    <template #bottom-buttons>
      <div class="gap-1 flex">
        <Button
          @click="onClickCloseModal"
          :disabled="isLoading"
          type="secondary"
        >
          Cancel
        </Button>
        <Button
          @click="onClickSubmit"
          :disabled="isLoading"
        >
          Create
        </Button>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import Button from '../../../components/Button/Button.vue';
import Modal from '../../../components/Modal/Modal.vue';
import { ref } from 'vue';
import Input from '../../../components/Input/Input.vue';
import { documentTemplateRepository } from '../../../repositories/documentTemplate.repository';
import { notify } from '@kyvg/vue3-notification';
import { useLoading } from '../../../composable/useLoading';
import { useRouter } from 'vue-router';

const router = useRouter();
const isOpenModal = ref(false);
const { isLoading, startLoading, stopLoading } = useLoading();

const getBlankFields = () => ({
  key: '',
  category: '',
  title: '',
});

const formFields = ref(getBlankFields());

const onClickCreate = () => {
  isOpenModal.value = true;
};

const onClickCloseModal = () => {
  isOpenModal.value = false;
  formFields.value = getBlankFields();
};

const onClickSubmit = async () => {
  startLoading();

  const data = await documentTemplateRepository.create({
    ...formFields.value,
  });

  stopLoading();

  if (!data) {
    return;
  }

  notify({
    type: 'success',
    title: 'Action OK',
    text: 'Document Template has been created.',
  });

  onClickCloseModal();

  router.push({
    name: 'document-template-edit',
    params: {
      uuid: data.uuid,
    },
  });
};
</script>

<style scoped></style>
