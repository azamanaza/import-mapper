import axios from 'axios';
import loaderState from '../state/loader.state';

export class BaseApiService {
  
  constructor() {
    this.baseUrl = 'http://127.0.0.1:8000/api/v1'
    this.entity = '';

    axios.interceptors.request.use((req) => {
      loaderState.show();
      return req;
    });

    axios.interceptors.response.use((res) => {
      loaderState.hide();
      return res;
    });
  }

  post(uri = '', params, config = {}) {
    return axios.post(this.buildUrl(uri), params, config)
  }

  get(uri, params, config = {}) {
    return axios.get(this.buildUrl(uri, params), config)
  }

  buildUrl(uri = '', params = {}) {
    const keys = Object.keys(params);
    const query = !!keys.length ? ('&' + keys.reduce((acc, key, index) => {
      const prefix = !index ? '' : '&'
      return `${acc}${prefix}${key}=${query[key]}`;
    }, '')) : '';
    return [this.baseUrl, this.entity, uri].join('/') + query;
  }
}
