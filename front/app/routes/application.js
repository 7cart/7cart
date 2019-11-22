import Route from '@ember/routing/route';
import RSVP from 'rsvp';
import { inject as service } from '@ember/service';
import ApplicationRouteMixin from 'ember-simple-auth/mixins/application-route-mixin';
import { getOwner } from '@ember/application';

export default Route.extend(ApplicationRouteMixin, {
  currentUser: service(),
  beforeModel(transition) {
    return this._loadCurrentUser();
  },

  async sessionAuthenticated() {
    await this._loadCurrentUser();

    const attemptedTransition = this.get('session.attemptedTransition');
    const cookies = getOwner(this).lookup('service:cookies');
    const redirectTarget = cookies.read('ember_simple_auth-redirectTarget');

    if (attemptedTransition) {
      attemptedTransition.retry();
      this.set('session.attemptedTransition', null);
    } else if (redirectTarget) {
      this.transitionTo(redirectTarget);
      cookies.clear('ember_simple_auth-redirectTarget');
    } else {
      // this.transitionTo(this.get('routeAfterAuthentication'));
    }
  },

  _loadCurrentUser() {
    return this.get('currentUser')
      .load()
      .then(() => {
        if (this.get('modalName') == 'registration' || this.get('modalName') == 'login') {
          this.send('closeModal');
        }
      })
      .catch((err) => console.log(err))
  },

  model() {
    return RSVP.hash({
      'categories': this.store.findAll('category')
    });
  },
  actions:{
    openModal: function(name) {
      this.set('modalName', name);
      this.render('modals/' + name, {
        into: 'application',
        outlet: 'modal'
      });
    },
    closeModal: function() {
      this.set('modalName', '');
      this.disconnectOutlet({
        outlet: 'modal',
        parentView: 'application'
      });
    },
    willTransition: function() {
      this.send('closeModal');
    }
  }
});
