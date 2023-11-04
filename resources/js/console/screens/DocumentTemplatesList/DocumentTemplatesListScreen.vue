<template>
  <Card>
    <Table
      title="Document Templates"
      sub-title="Manage all document templates across your product."
      :columns="columns"
      :records="records"
    >
      <template #action-buttons>
        <CreateNewTemplate />
      </template>
      <template #record-actions="{ record }">
        <span class="isolate inline-flex rounded-md shadow-sm gap-1">
          <Button
            @click="
              $router.push({
                name: 'document-template-edit',
                params: { uuid: record.uuid },
              })
            "
          >
            Edit
          </Button>
          <DuplicateTemplateButton
            :template="record"
            @duplicated="onTemplateDuplicated"
          />
          <DeleteTemplateButton
            :template="record"
            @deleted="onTemplateDeleted"
          />
        </span>
      </template>
      <template #before-table>
        <div class="py-4 flex -mx-2 -my-2 sm:-mx-6 lg:-mx-8 gap-2">
          <div class="flex-1">
            <Input
              v-model="keyword"
              label=""
              placeholder="Search by keyword"
              @keyup.enter="search"
            />
          </div>
          <div class="flex-1 mt-2">
            <Button @click="search">Search</Button>
          </div>
        </div>
      </template>
      <template #after-table>
        <Pagination
          v-if="paginationMeta"
          :total-records="paginationMeta.total"
          :total-pages="paginationMeta.last_page"
          :current="page"
          @go-to-page="loadRecords"
        />
      </template>
    </Table>
  </Card>
</template>

<script setup>
import Table from '../../components/Table/Table.vue';
import Card from '../../components/Card/Card.vue';
import { ref } from 'vue';
import { documentTemplateRepository } from '../../repositories/documentTemplate.repository';
import Button from '../../components/Button/Button.vue';
import CreateNewTemplate from './components/CreateNewTemplate.vue';
import DeleteTemplateButton from './components/DeleteTemplateButton.vue';
import DuplicateTemplateButton from './components/DuplicateTemplateButton.vue';
import { useRouter } from 'vue-router';
import Input from '../../components/Input/Input.vue';
import { notify } from '@kyvg/vue3-notification';
import Pagination from '../../components/Pagination/Pagination.vue';

const router = useRouter();

const columns = [
  {
    key: 'uuid',
    label: 'ID',
  },
  {
    key: 'key',
    label: 'Key',
  },
  {
    key: 'category',
    label: 'Category',
  },
  {
    key: 'title',
    label: 'Title',
  },
  {
    key: 'updated_at',
    label: 'Last Updated At',
    transform(value) {
      return new Date(value).toLocaleString();
    },
  },
];

const records = ref([]);
const paginationMeta = ref(null);
const page = ref(1);
const keyword = ref(null);
const isSearching = ref(false);

const loadRecords = async (forcePage) => {
  page.value = forcePage || page.value;

  const documentTemplates = await documentTemplateRepository.index({
    limit: 20,
    page: page.value,
    search: keyword.value,
  });

  records.value = [...documentTemplates.data];
  paginationMeta.value = { ...documentTemplates.meta };

  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const onTemplateDeleted = () => loadRecords();
const onTemplateDuplicated = (result) =>
  router.push({
    name: 'document-template-edit',
    params: { uuid: result.template.uuid },
  });

// inits
loadRecords();

function search() {
  if (!keyword.value) {
    // clear search state
    if (isSearching.value) {
      page.value = 1;
      isSearching.value = false;
      loadRecords();

      return;
    }

    notify({
      type: 'error',
      title: 'Missing Info',
      text: 'Please enter some keywords in order to search.',
    });

    return;
  }

  isSearching.value = true;

  // refetch
  loadRecords(1);
}
</script>

<style scoped></style>
