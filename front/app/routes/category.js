import Route from '@ember/routing/route';
import {inject as service} from '@ember/service';

export default Route.extend({
  breadcrumbs: service(),
  model: function (params) {
    return this.store.peekRecord('category', params.id);
  },
  setupController: function(controller, model) {
    this.get('breadcrumbs').setCurrentCategory(model);
    this._super(controller, model);
  },
  actions: {
    willTransition() {
      this.get('breadcrumbs').setCurrentCategory(null);
    }
  }
});
