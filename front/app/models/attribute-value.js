import DS from 'ember-data';
import { computed } from '@ember/object';

export default DS.Model.extend({
  value: DS.attr('string'),
  attribute: DS.belongsTo('attribute'),
});
