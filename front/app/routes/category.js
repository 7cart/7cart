import Route from '@ember/routing/route';
import {inject as service} from '@ember/service';

export default Route.extend({
  breadcrumbs: service(),
  model: function (params) {
    return this.store.peekRecord('category', params.id);
  },
  afterModel: function (model) {
    if (model.get('nodes')){
      model.set('nodes.isLoaded', false);
    }
    Ember.RSVP.hash({
      nodes: this.store.query('node', {'category_id': model.get('id')})
    }).then((res) => {
      model.set('nodes', res.nodes);
    });
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
