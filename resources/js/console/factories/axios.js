import axios from 'axios';
import { authStorage } from '../utils/authStorage';

const options = {
  baseURL: '/api/v1',
  timeout: 60_000,
};

export const publicInstance = axios.create(options);
export const authenticatedInstance = axios.create(options);

authenticatedInstance.interceptors.request.use((config) => {
  config.headers['X-Access-Key'] = authStorage.get();

  return config;
});
