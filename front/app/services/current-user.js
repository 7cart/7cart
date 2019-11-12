import Service from '@ember/service';
import { resolve } from 'rsvp';
import { inject as service } from '@ember/service';

export default Service.extend({
  session: service(),
  store: service(),

  load() {
    if (this.get('session.isAuthenticated')) {
      return this.get('store').queryRecord('user', { me: true }).then((user) => {
        this.set('user', user);
      });
    } else {
      return resolve();
    }
  }
});
