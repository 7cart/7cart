import Helper from '@ember/component/helper';
import {inject as service} from '@ember/service';
import { observer } from '@ember/object';

export default Helper.extend({
  bcService: service('breadcrumbs'),
  onNewUser: observer('bcService.breadcrumbs', function() {
    this.recompute();
  }),
  compute(catId) {
    return this.get('bcService').breadcrumbs.some((cat) => {
      return (cat.get('id') == catId) ? true : false;
    });
  }
});
