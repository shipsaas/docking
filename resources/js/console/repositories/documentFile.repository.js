import { getAuthenticatedInstance } from '../factories/axios';
import { catchError, getData } from './helper';

const httpClient = getAuthenticatedInstance();

export const documentFileRepository = {
  index({
    limit = 20,
    page = 1,
    sortBy = 'created_at',
    sortDirection = 'asc',
  }) {
    return httpClient
      .get('document-files', {
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
      .get(`document-files/${id}`)
      .then(getData)
      .catch(catchError);
  },
};
