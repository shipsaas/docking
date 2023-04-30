export const toJsonString = (object) => {
  if (object === null) {
    return '{}';
  }

  if (typeof object === 'object') {
    return JSON.stringify(object);
  }

  return '{}';
};
