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

  show(id) {},

  create() {},

  update(id, record) {},

  destroy(id) {},
};
