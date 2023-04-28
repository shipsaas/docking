import { notify } from '@kyvg/vue3-notification';

/**
 * Get data from Axios Response
 *
 * @param {AxiosResponse<Object>} data
 */
export const getData = (data) => data.data;

/**
 * Gratefully Catch Axios Error - Avoid throw
 *
 * @param {AxiosError} error
 */
export const catchError = (error) => {
  const response = error.response.data;

  if (response.outcome) {
    notify({
      type: 'error',
      title: 'Request Error',
      text: response.outcome,
    });

    return;
  }

  // show generic error.
  notify({
    type: 'error',
    title: 'Request Error',
    text: 'Failed to make the API request. Please try again.',
  });
};
