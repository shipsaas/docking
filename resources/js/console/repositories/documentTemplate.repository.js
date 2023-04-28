import { getAuthenticatedInstance } from '../factories/axios';
import { catchError, getData } from './helper';

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
      .then(getData)
      .catch(catchError);
  },

  show(id) {
    return httpClient
      .get(`document-templates/${id}`)
      .then(getData)
      .catch(catchError);
  },

  create(inputs) {
    return httpClient
      .post(`document-templates/`, inputs)
      .then(getData)
      .catch(catchError);
  },

  update(id, inputs) {
    return httpClient
      .put(`document-templates/${id}`, inputs)
      .then(getData)
      .catch(catchError);
  },

  destroy(id) {
    return httpClient
      .delete(`document-templates/${id}`)
      .then(getData)
      .catch(catchError);
  },

  previewHtml(id) {
    return httpClient
      .get(`document-templates/${id}/preview-html`)
      .then(getData)
      .catch(catchError);
  },
};
