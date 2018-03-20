import Component from '@ember/component';
import {inject as service} from '@ember/service';
import EmberObject from '@ember/object';
import {observer} from '@ember/object';

export default Component.extend({
  router: service(),
  filter: service(),
  onFilterChange: observer('filter.filterStr', function () {
    if (!this.get('filter.filterStr')) {
      this.get('filter').cleanUp(this.get('selectedFilter'));
    }
  }),
  init() {
    this._super(...arguments);
    this.set('selectedFilter', EmberObject.create());
    this.get('filter').populate(this.get('selectedFilter'));
  },
  willDestroy() {
    this.get('selectedFilter').destroy();
  },
  actions: {
    filtering(attrId, newValues) {
      this.get('selectedFilter').set(attrId, newValues);
      let newFilter = this.get('filter').toString(this.get('selectedFilter'));
      this.get('router').transitionTo('category.filter', newFilter);
    }
  }
});
