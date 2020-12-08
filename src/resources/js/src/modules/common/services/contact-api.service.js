import { BaseApiService } from './base-api.service';

export class ContactApiService extends BaseApiService {

  constructor() {
    super();
    this.entity = 'contacts';
  }

  import(csvFile) {
    console.log('importing:: ', { csvFile } );
    const formData = new FormData();
    formData.append('csvFile', csvFile, csvFile.name);
    return this.post('import', formData);
  }

  list() {
    return this.get('list');
  }
}
