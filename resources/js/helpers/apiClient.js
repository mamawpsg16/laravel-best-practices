import axios from 'axios';

const makeRequest = async (method, url, data = null, config = {}) => {
  try {
    const response = await axios({
      method,
      url,
      data,
      ...config,
    });
    return response;
  } catch (error) {
    throw error;
  }
};

const apiClient = {
  get: async (url, config = {}) => {
    return makeRequest('GET', url, null, config);
  },
  post: async (url, data, config = {}) => {
    return makeRequest('POST', url, data, config);
  },
  put: async (url, data, config = {}) => {
    return makeRequest('PUT', url, data, config);
  },
  patch: async (url, data, config = {}) => {
    return makeRequest('PATCH', url, data, config);
  },
  delete: async (url, config = {}) => {
    return makeRequest('DELETE', url, null, config);
  },
};

export default apiClient;