import Component from '@ember/component';
import { inject as service } from '@ember/service';

export default Component.extend({
  session: service(),
  currentUser: service(),
  actions: {
    invalidateSession() {
      this.get('session').invalidate();
    }
  }
});
