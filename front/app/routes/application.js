import Route from '@ember/routing/route';
import RSVP from 'rsvp';
import { on } from '@ember/object/evented';

export default Route.extend({
  model() {
    return RSVP.hash({
      'categories': this.store.findAll('category')
    });
  }
});
