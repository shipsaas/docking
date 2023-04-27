import axios from 'axios';
import { authStorage } from '../utils/authStorage';

const options = {
  baseURL: import.meta.env.VITE_APP_URL + '/api/v1',
  timeout: 60_000,
};

export const getPublicInstance = () => axios.create(options);

export const getAuthenticatedInstance = () =>
  axios.create({
    ...options,
    headers: {
      'X-Access-Key': authStorage.get(),
    },
  });
