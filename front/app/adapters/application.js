import DS from 'ember-data';
import config from 'front/config/environment';
import { isPresent } from '@ember/utils';
import { computed } from '@ember/object';
import DataAdapterMixin from 'ember-simple-auth/mixins/data-adapter-mixin';
import { inject as service } from '@ember/service';

export default DS.JSONAPIAdapter.extend(DataAdapterMixin,{
  fastboot: service(),
  host: computed('fastboot.isFastBoot', function() {
    let fastboot = this.fastboot;

    if (fastboot.isFastBoot) {
      // docker network alias
      return config.APP.backendDockerHost;
    } else {
      return config.APP.backendHost;
    }
  }),
  namespace: 'api/v1',
  init() {
    this._super(...arguments);
    this.headers = {
      'Accept-Language':'en'
    };
  },
  authorize(xhr) {
    let { access_token } = this.get('session.data.authenticated');
    if (isPresent(access_token)) {
      xhr.setRequestHeader('Authorization', `Bearer ${access_token}`);
    }
  },
});
