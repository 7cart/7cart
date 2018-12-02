import DS from 'ember-data';
import { computed } from '@ember/object';

export default DS.Model.extend({
  name: DS.attr('string'),
  attributeValues:  DS.hasMany('attribute-value'),
  dataType: DS.attr('string'),
  isRange: computed('dataType', function() {
    return (this.get('dataType') == 'integer' || this.get('dataType') == 'float');
  })
});
