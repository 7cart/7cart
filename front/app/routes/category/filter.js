import Route from '@ember/routing/route';
import {inject as service} from '@ember/service';
import { on } from '@ember/object/evented';

export default Route.extend({
  filter: service(),
  clearFilter: on('deactivate', function(){
    this.set('filter.filterStr', '');
  }),
  model(params) {
      this.set('filter.filterStr', params.filter);
  }
});
