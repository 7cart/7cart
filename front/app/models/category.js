import DS from 'ember-data';
import { computed } from '@ember/object';

export default DS.Model.extend({
  title: DS.attr('string'),
  parentId:  DS.attr('string'),
  children: DS.hasMany('category', { inverse: null }),
  isTopLevel: computed('parentId', function() {
    return (this.get('parentId') == 0);
  })
});
