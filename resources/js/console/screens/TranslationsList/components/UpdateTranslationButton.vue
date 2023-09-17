<template>
  <Button @click="onClickUpdate">Update</Button>
  <Modal
    title="Update Translation"
    width-size="xl"
    :is-open="isOpenModal"
  >
    <template #default>
      <div class="flex flex-col mt-4 gap-2">
        <Dropdown
          :label="selectedTranslationGroupLabel"
          :items="translationGroupDropdownItems"
          @selected="handleSelectedTranslationGroup"
        />
        <Input
          v-model="formFields.key"
          label="Unique Identifier Key"
          :disabled="isLoading"
        />
        <p
          v-if="selectedTranslationGroup"
          class="text-sm"
        >
          Your full translation key will be:
          <span class="font-medium">
            {{ selectedTranslationGroup.key }}.{{ formFields.key }}
          </span>
        </p>
        <Input
          v-model="formFields.name"
          label="Name"
          :disabled="isLoading"
        />
        <h3 class="font-medium text-sm">Translation Texts</h3>
        <div
          v-for="language in languages"
          :key="language.code"
          class="flex flex-row items-center gap-3"
        >
          <span class="font-medium text-sm">
            {{ language.name }} ({{ language.code }})
          </span>
          <Input
            v-model="formFields.text[language.code]"
            class="flex-1"
            label=""
          />
        </div>
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
import { computed, ref } from 'vue';
import Input from '../../../components/Input/Input.vue';
import { notify } from '@kyvg/vue3-notification';
import { useLoading } from '../../../composable/useLoading';
import { useRouter } from 'vue-router';
import { translationRepository } from '../../../repositories/translation.repository';
import Dropdown from '../../../components/Dropdown/Dropdown.vue';

const props = defineProps({
  translation: {
    type: Object,
    required: true,
  },
  languages: {
    type: Array,
    required: true,
  },
  translationGroups: {
    type: Array,
    required: true,
  },
});

const router = useRouter();
const isOpenModal = ref(false);
const { isLoading, startLoading, stopLoading } = useLoading();
const emits = defineEmits(['updated']);

const getBlankFields = () => ({
  translation_group_id: '',
  key: '',
  name: '',
  text: props.languages.reduce((values, item) => {
    values[item.code] = '';

    return values;
  }, {}),
});

const formFields = ref(getBlankFields());

const onClickUpdate = () => {
  isOpenModal.value = true;
  formFields.value = getBlankFields();

  handleSelectedTranslationGroup(props.translation.translation_group.uuid);
  formFields.value.key = props.translation.key.replace(
    `${props.translation.translation_group.key}.`,
    ''
  );
  formFields.value.name = props.translation.name;
  formFields.value.text = props.translation.text;
};

const onClickCloseModal = () => {
  isOpenModal.value = false;
};

const onClickSubmit = async () => {
  startLoading();

  const data = await translationRepository.update(props.translation.uuid, {
    ...formFields.value,
  });

  stopLoading();

  if (!data) {
    return;
  }

  notify({
    type: 'success',
    title: 'Action OK',
    text: 'Translation has been updated.',
  });

  onClickCloseModal();
  emits('updated');
};

const translationGroupDropdownItems = computed(() =>
  props.translationGroups.map((item) => ({
    key: item.uuid,
    label: item.name,
  }))
);

const selectedTranslationGroup = computed(() =>
  props.translationGroups.find(
    (item) => item.uuid === formFields.value.translation_group_id
  )
);

const selectedTranslationGroupLabel = computed(
  () => selectedTranslationGroup.value?.name || 'Select Translation Group'
);

const handleSelectedTranslationGroup = (translationGroupId) => {
  formFields.value.translation_group_id = translationGroupId;
};
</script>
