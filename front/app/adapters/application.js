import DS from 'ember-data';

export default DS.JSONAPIAdapter.extend({
  host: 'http://localhost:8000',
  namespace: 'api/v1',
  headers: {
    'Accept-Language':'en'
  }
});
