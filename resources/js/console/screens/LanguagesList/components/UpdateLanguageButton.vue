<template>
  <Button @click="onClickCreate">Update</Button>
  <Modal
    title="Update Language"
    :is-open="isOpenModal"
  >
    <template #default>
      <div class="flex flex-col mt-4 gap-2">
        <Input
          :model-value="language.code"
          label="Language ISO Code"
          disabled
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
          Update
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

const props = defineProps({
  language: {
    type: Object,
    required: true,
  },
});
const emits = defineEmits(['updated']);

const router = useRouter();
const isOpenModal = ref(false);
const { isLoading, startLoading, stopLoading } = useLoading();

const getBlankFields = () => ({
  name: '',
});

const formFields = ref(getBlankFields());

const onClickCreate = () => {
  isOpenModal.value = true;
  formFields.value.name = props.language.name;
};

const onClickCloseModal = () => {
  isOpenModal.value = false;
  formFields.value = getBlankFields();
};

const onClickSubmit = async () => {
  startLoading();

  console.log(props.language);
  const data = await languageRepository.update(props.language.uuid, {
    ...formFields.value,
  });

  stopLoading();

  if (!data) {
    return;
  }

  notify({
    type: 'success',
    title: 'Action OK',
    text: 'Language has been updated.',
  });

  onClickCloseModal();
  emits('updated');
};
</script>

<style scoped></style>
