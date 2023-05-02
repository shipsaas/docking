export const toJsonString = (object) => {
  if (object === null) {
    return '{}';
  }

  if (typeof object === 'object') {
    return JSON.stringify(object);
  }

  return '{}';
};

export const validateJson = (string) => {
  try {
    return JSON.parse(string);
  } catch (e) {
    return false;
  }
};
