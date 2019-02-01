import DS from 'ember-data';
import {inject as service} from '@ember/service';

export default DS.JSONAPISerializer.extend({
  _store: service('store'),
  normalizeResponse(store, primaryModelClass, payload, id, requestType) {
    //fix meta data not suported for findRecord
    if (payload.meta && payload.meta.attributes) {
      this.store.pushPayload(payload.meta.attributes);
    }
    return this._super(...arguments);
  },
});
