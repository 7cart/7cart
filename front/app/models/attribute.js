import DS from 'ember-data';
import { computed } from '@ember/object';

export default DS.Model.extend({
  name: DS.attr('string'),
  attributeValues:  DS.hasMany('attribute-value'),
  dataType: DS.attr('string'),
  isMultiValues: DS.attr('boolean'),
  isRelated: DS.attr('boolean'),
  isNumeric: DS.attr('boolean')
});
