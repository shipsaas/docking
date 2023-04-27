import { getAuthenticatedInstance } from '../factories/axios';

const httpClient = getAuthenticatedInstance();

export const documentTemplateRepository = {
  index({
    limit = 20,
    page = 1,
    sortBy = 'created_at',
    sortDirection = 'asc',
  }) {
    return httpClient
      .get('document-templates', {
        params: {
          limit,
          page,
          sort_by: sortBy,
          sort_direction: sortDirection,
        },
      })
      .then((response) => response.data);
  },

  show(id) {
    return httpClient
      .get(`document-templates/${id}`)
      .then((response) => response.data);
  },

  create(inputs) {
    return httpClient
      .post(`document-templates/`, inputs)
      .then((response) => response.data);
  },

  update(id, inputs) {
    return httpClient
      .put(`document-templates/${id}`, inputs)
      .then((response) => response.data);
  },

  destroy(id) {
    return httpClient
      .delete(`document-templates/${id}`)
      .then((response) => response.data);
  },
};
