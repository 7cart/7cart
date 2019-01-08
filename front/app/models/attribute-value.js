import DS from 'ember-data';

export default DS.Model.extend({
  value: DS.attr('string'),
  attribute: DS.belongsTo('attribute'),
});
