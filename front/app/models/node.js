import DS from 'ember-data';
import { computed } from '@ember/object';

export default DS.Model.extend({
  title: DS.attr('string'),
  attachments: DS.hasMany('attachment'),
  firstAttachmentURL: computed('attachments', function() {
    let fo = this.get('attachments.firstObject');
    if (fo) {
      return fo.get('fileName');
    } else {
      return 'http://placehold.it/400x250/000/fff'
    }
  })
});
