import Route from '@ember/routing/route';
import RSVP from 'rsvp';

export default Route.extend({
  model: function () {
    return RSVP.hash({
      'categories': this.store.findAll('category')
    });
  }
});
