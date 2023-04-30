import { publicInstance } from '../factories/axios';

export const accessRepository = {
  async access(password) {
    const response = await publicInstance
      .get('access', {
        headers: {
          'X-Access-Key': password,
        },
      })
      .catch(() => true);

    return !!response?.data;
  },
};
