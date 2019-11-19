import DS from 'ember-data';
import attr from 'ember-data/attr';

export default DS.Model.extend({
  email: attr('string'),
  name: attr('string'),
  plainPassword: attr('string'),
});
