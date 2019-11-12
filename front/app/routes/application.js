import Route from '@ember/routing/route';
import RSVP from 'rsvp';
import { inject as service } from '@ember/service';
import ApplicationRouteMixin from 'ember-simple-auth/mixins/application-route-mixin';

export default Route.extend(ApplicationRouteMixin, {
  currentUser: service(),
  beforeModel() {
    return this._loadCurrentUser();
  },

   async sessionAuthenticated() {
     let _super = this._super;
     await this._loadCurrentUser();
     _super.call(this, ...arguments);
   },

  _loadCurrentUser() {
    return this.get('currentUser').load().catch((err) => console.log(err)/*this.get('session').invalidate()*/);
  },

  model() {
    return RSVP.hash({
      'categories': this.store.findAll('category')
    });
  },
  actions:{
    openModal: function(name) {
      this.render('modals/' + name, {
        into: 'application',
        outlet: 'modal',
        model: null
      });
    },
    closeModal: function(){
      this.disconnectOutlet({
        outlet: 'modal',
        parentView: 'application'
      });
    },
  }
});
