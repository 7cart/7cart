import Component from '@ember/component';
import { inject as service } from '@ember/service';
import Object from '@ember/object';

export default Component.extend({
  session: service(),
  init() {
    this._super(...arguments);
    this.set('processing', false);
    this.set('errorMessage', '');
  },
  actions: {
    authenticate() {
      if (this.get('processing')) {
        return;
      }

      let { identification, password } = this.getProperties('identification', 'password');
      this.set('processing', true);
      this.get('session').authenticate('authenticator:oauth2', identification, password).catch((reason) => {
         this.set('errorMessage', Object.create(reason).get('error_description'));
      }).finally(() => {
        this.set('processing', false);
      });
    }
  }
});
