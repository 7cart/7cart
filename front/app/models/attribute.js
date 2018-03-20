import DS from 'ember-data';
import { computed } from '@ember/object';

export default DS.Model.extend({
  name: DS.attr('string'),
  attributeValues:  DS.hasMany('attribute-value')
});
