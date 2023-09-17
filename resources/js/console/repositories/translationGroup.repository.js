import { authenticatedInstance } from '../factories/axios';
import { catchError, getData } from './helper';

const httpClient = authenticatedInstance;

export const translationGroupRepository = {
  index({
    limit = 20,
    page = 1,
    sortBy = 'created_at',
    sortDirection = 'asc',
  }) {
    return httpClient
      .get('translation-groups', {
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

  /**
   * @param {{name: string, description: string, key: string}} data
   */
  create(data) {
    return httpClient
      .post(`translation-groups`, data)
      .then(getData)
      .catch(catchError);
  },

  /**
   * @param {String} id
   * @param {{name: string, description: string}} data
   */
  update(id, data) {
    return httpClient
      .put(`translation-groups/${id}`, data)
      .then(getData)
      .catch(catchError);
  },

  destroy(id) {
    return httpClient
      .delete(`translation-groups/${id}`)
      .then(getData)
      .catch(catchError);
  },
};
