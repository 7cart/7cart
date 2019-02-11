import Service from '@ember/service';
import {A} from '@ember/array';

export default Service.extend({
  init() {
    this.set('prefix', 'f_');
    this.set('filterCounter', []);
  },
  cleanUp(filterObj) {
    Object.keys(filterObj).forEach((key) => {
      filterObj.set(key, A());
    });
  },
  toString(filterObj) {
    let sections = [];
    Object.keys(filterObj).forEach((key) => {
      let values = filterObj.get(key);
      if (values) {
        if (values.hasOwnProperty('min') && values.hasOwnProperty('max')) {
          sections.push(key + '=' + values.min + '_' + values.max);
        } else if (values.length > 0) {
          sections.push(key + '=' + values.join(","));
        }
      }
    });
    return this.get('prefix') + sections.join(';');
  },
  populate(filterObj) {
    // filterStr - goes from route param
    if (!this.get('filterStr')) {
      return filterObj;
    }

    let filterStr = this.get('filterStr').toLowerCase().replace(this.get('prefix'), '');
    filterStr.split(';').forEach((section) => {
      if (section) {
        let [attr, values] = section.split('=');
        if (values && attr) {
          let attrId = attr;
          let found = values.match(/(.+)_(.*)/i);
          if (found) {
            if (found.length == 3) {
              filterObj.set(attrId, A({"min": parseFloat(found[1]) || 0, "max": parseFloat(found[2]) || ''}));
            }
          } else {
            filterObj.set(attrId, A(values.split(',').map((v) => {
              return v;
            })));
          }
        }
      }
    });

    return filterObj;
  }
});
