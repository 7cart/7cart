import Service from '@ember/service';
import {inject as service} from '@ember/service';

export default Service.extend({
  store: service(),
  init() {
    this._super(...arguments);
    this.set('breadcrumbs', []);
    this.set('currentCategory', null);
  },
  setCurrentCategory: function(category) {
    this.set('currentCategory', category);
    this.set('breadcrumbs', this._buildBreadcrumb(category).reverse());
  },
  _buildBreadcrumb: function(category) {
    let bc = [];
    if (category && category.get('id')) {
      bc.push(category);
      if (category.get('parentId') > 0) {
        this._buildBreadcrumb(this.get('store').peekRecord('category', category.get('parentId'))).map((i) => {
          bc.push(i);
        });
      }
    }
    return bc;
  }
});
