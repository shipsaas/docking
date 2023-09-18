<template>
  <Button @click="onClickCreate">Create New Language</Button>
  <Modal
    title="Create New Language"
    :is-open="isOpenModal"
  >
    <template #default>
      <div class="flex flex-col mt-4 gap-2">
        <Input
          v-model="formFields.code"
          label="Language ISO Code (e.g: en, vi, es)"
          :disabled="isLoading"
        />
        <Input
          v-model="formFields.name"
          label="Name"
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
import { notify } from '@kyvg/vue3-notification';
import { useLoading } from '../../../composable/useLoading';
import { useRouter } from 'vue-router';
import { languageRepository } from '../../../repositories/language.repository';

const router = useRouter();
const isOpenModal = ref(false);
const { isLoading, startLoading, stopLoading } = useLoading();
const emits = defineEmits(['created']);

const getBlankFields = () => ({
  code: '',
  name: '',
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

  const data = await languageRepository.create({
    ...formFields.value,
  });

  stopLoading();

  if (!data) {
    return;
  }

  notify({
    type: 'success',
    title: 'Action OK',
    text: 'Language has been created.',
  });

  onClickCloseModal();
  emits('created');
};
</script>

<style scoped></style>
