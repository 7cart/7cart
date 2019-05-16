import DS from 'ember-data';
import config from 'front/config/environment';

export default DS.JSONAPIAdapter.extend({
  host: config.APP.backendHost,
  namespace: 'api/v1',
  init() {
    this._super(...arguments);
    this.headers = {
      'Accept-Language':'en'
    };
  }
});
