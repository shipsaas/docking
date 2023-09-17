import { authenticatedInstance } from '../factories/axios';
import { catchError, getData } from './helper';

const httpClient = authenticatedInstance;

export const languageRepository = {
  index({
    limit = 20,
    page = 1,
    sortBy = 'created_at',
    sortDirection = 'asc',
  }) {
    return httpClient
      .get('languages', {
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
   * @param {{name: string, code: string}} data
   */
  create(data) {
    return httpClient.post(`languages`, data).then(getData).catch(catchError);
  },

  destroy(id) {
    return httpClient.delete(`languages/${id}`).then(getData).catch(catchError);
  },
};
