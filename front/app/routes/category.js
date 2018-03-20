import Route from '@ember/routing/route';
import {inject as service} from '@ember/service';
import {observer} from '@ember/object';
import {on} from '@ember/object/evented';

export default Route.extend({
  breadcrumbs: service(),
  filter: service(),
  clearBreadcrumbs: on('deactivate', function () {
    this.get('breadcrumbs').setCurrentCategory(null);
  }),
  onFilterChange: observer('filter.filterStr', function () {
    this.refresh();
  }),
  model(params) {
      return this.store.peekRecord('category', params.id);
  },
  afterModel(model) {
    if (model.get('nodes')) {
      model.set('nodes.isLoaded', false);
    }
    model.set('attributes', this.store.peekAll('attribute'));
    Ember.RSVP.hash({
      nodes: this.store.query('node', {'category_id': model.get('id'), 'f': this.get('filter.filterStr')})
    }).then((res) => {
      model.set('nodes', res.nodes);
    });
  },
  setupController(controller, model) {
    this.get('breadcrumbs').setCurrentCategory(model);
    this._super(controller, model);
  }
});
