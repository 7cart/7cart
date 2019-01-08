import Route from '@ember/routing/route';

import {inject as service} from '@ember/service';
import {observer} from '@ember/object';
import {on} from '@ember/object/evented';
import { resolve } from 'rsvp';
import { get } from '@ember/object';
import InfinityModel from 'ember-infinity/lib/infinity-model';

const ExtendedInfinityModel = InfinityModel.extend({
  filter: service(),
  buildParams() {
    let params = this._super(...arguments);
    if (this.get('firstLoad')){
      params['event'] = 'load_more';
    }
    return params;
  },
  afterInfinityModel(nodes) {

    if (nodes.get('meta.attributes')) {
      this.store.pushPayload(nodes.get('meta.attributes'));
      this.get('filter').set('attributes', this.get('store').peekAll('attribute'));
    }

    if (nodes.get('meta.filter-counter')) {
      this.get('filter').set('filterCounter', nodes.get('meta.filter-counter'));
    }

    return resolve(nodes).then((nodes) => {
      this.set('firstLoad', true);
      return nodes;
    });
  }
});

export default Route.extend({
  router: service(),
  breadcrumbs: service(),
  infinity: service(),
  filter: service(),
  clearBreadcrumbs: on('deactivate', function () {
    this.get('breadcrumbs').setCurrentCategory(null);
  }),
  onFilterChange: observer('filter.filterStr', function () {
    this.set('event', 'filter');
    this.refresh();
  }),
  model(params) {
    return this.get('store').peekRecord('category', params.id);
  },
  afterModel(model, transition) {

    if (!model) {
      return;
    }

    let params = {
      'startingPage': get(transition, 'queryParams.page'),
      'category_id': model.get('id'),
      'f': this.get('filter.filterStr')
    };

    if (this.get('event')){
      params['event'] = this.get('event');
    }

    model.set('nodes', this.infinity.model('node', params , ExtendedInfinityModel));

    this.set('event', null);
  },
  setupController(controller, model) {
    this.get('breadcrumbs').setCurrentCategory(model);
    this._super(controller, model);
  },
  actions:{
    goToPage(pageNo){
      this.get('router').transitionTo('category.filter', {
        queryParams: {
          page: pageNo
        }
      });
      this.set('event', 'page');
      this.refresh();
    }
  }
});
