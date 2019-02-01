import DS from 'ember-data';
import { computed } from '@ember/object';
import {A} from '@ember/array';

export default DS.Model.extend({
  title: DS.attr('string'),
  description: DS.attr('string'),
  attributes: DS.attr('jsonb'),
  attachments: DS.hasMany('attachment'),
  categoriesId: DS.attr('jsonb'),
  firstCategoryId: computed('categoriesId', function() {
    return this.get('categoriesId.firstObject');
  }),
  firstAttachmentURL: computed('attachments', function() {
    let fo = this.get('attachments.firstObject');
    if (fo) {
      return fo.get('fileName');
    } else {
      return 'http://placehold.it/400x250/000/fff'
    }
  }),
  computedAttributes: computed('attributes', function() {
    let resArr = A();
    let attrObj = this.get('attributes');
    let allAttributes = this.get('store').peekAll('attribute');
    Object.keys(attrObj).forEach((key) => {
      let currentAttribute = allAttributes.findBy('name', key);
      if (currentAttribute) {
        let resObj = {
          computedName: key,
          attribute: currentAttribute
        };

        if (currentAttribute.get('isRelated')) {
          let ids = (Array.isArray(attrObj[key])) ? attrObj[key] : [attrObj[key]] ;
          let values = [];

          currentAttribute.get('attributeValues').forEach((av) => {
            if (ids.includes(parseInt(av.get('id')))) {
              values.push(av.get('value'));
            }
          });

          resObj.computedValue = values.join(', ');
        } else {
          resObj.computedValue = attrObj[key];
        }

        resArr.pushObject(resObj);
      }
    });

    return resArr;
  })
});
