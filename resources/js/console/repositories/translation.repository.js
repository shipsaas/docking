import { authenticatedInstance } from '../factories/axios';
import { catchError, getData } from './helper';

const httpClient = authenticatedInstance;

export const fontRepository = {
  index({
    limit = 20,
    page = 1,
    sortBy = 'created_at',
    sortDirection = 'asc',
  }) {
    return httpClient
      .get('fonts', {
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
   *
   * @param {FormData} data
   */
  create(data) {
    return httpClient
      .post(`fonts`, data, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
      .then(getData)
      .catch(catchError);
  },

  destroy(id) {
    return httpClient.delete(`fonts/${id}`).then(getData).catch(catchError);
  },
};
