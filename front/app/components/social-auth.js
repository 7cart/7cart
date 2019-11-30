import Component from '@ember/component';
import { inject as service } from '@ember/service';

export default Component.extend({
  torii: service(),
  session: service(),
  actions: {
    socilAuth(provider) {
      this.get('torii').open(provider).then((authorization) => {
        this.get('session').authenticate('authenticator:oauth2', provider, JSON.stringify(authorization));
      })
    }
  }
});
