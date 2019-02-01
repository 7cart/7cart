import Route from '@ember/routing/route';
import {inject as service} from '@ember/service';

export default Route.extend({
  breadcrumbs: service(),
  model(params) {
    return this.get('store').findRecord('node', params.id);
  },
  setupController(controller, model) {
    this.get('breadcrumbs').setCurrentCategory(this.get('store').peekRecord('category', model.get('firstCategoryId')));
    this._super(controller, model);
  },
});
