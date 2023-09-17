<template>
  <Button @click="onClickCreate">Update</Button>
  <Modal
    title="Update Language"
    :is-open="isOpenModal"
  >
    <template #default>
      <div class="flex flex-col mt-4 gap-2">
        <Input
          :model-value="translationGroup.key"
          label="Unique Key"
          disabled
        />
        <Input
          v-model="formFields.name"
          label="Name"
          :disabled="isLoading"
        />
        <Input
          v-model="formFields.description"
          label="Description (Optional)"
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
import { translationGroupRepository } from '../../../repositories/translationGroup.repository';

const props = defineProps({
  translationGroup: {
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
  description: '',
});

const formFields = ref(getBlankFields());

const onClickCreate = () => {
  isOpenModal.value = true;
  formFields.value.name = props.translationGroup.name;
  formFields.value.description = props.translationGroup.description;
};

const onClickCloseModal = () => {
  isOpenModal.value = false;
  formFields.value = getBlankFields();
};

const onClickSubmit = async () => {
  startLoading();

  const data = await translationGroupRepository.update(
    props.translationGroup.uuid,
    {
      ...formFields.value,
    }
  );

  stopLoading();

  if (!data) {
    return;
  }

  notify({
    type: 'success',
    title: 'Action OK',
    text: 'Translation Group has been updated.',
  });

  onClickCloseModal();
  emits('updated');
};
</script>

<style scoped></style>
