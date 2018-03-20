import Service from '@ember/service';
import {A} from '@ember/array';

export default Service.extend({
  init() {
    this.set('prefix', 'f_');
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
      if (values && values.length > 0) {
        sections.push(key + '=' + values.join(","));
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
          if (values.indexOf('-') > -1) {
            let found = values.match(/(\d*)-(\d*)/i);
            if (found && found.length == 3) {
              filterObj.set(attrId, A([{'min': found[1] || 0, 'max': found[2] || 0}]));
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
