import Helper from '@ember/component/helper';
import {inject as service} from '@ember/service';

export default Helper.extend({
  filter: service(),
  compute(data) {
    let count = 0;
    let valId = data[0] || 0;
    let attrId = data[1] || 0;

    if (this.get('filter.filterCounter')) {
      this.get('filter.filterCounter').some((i) => {
        //console.log(valId, i['val_id'] , attrId , i['attr_id']);
          if (valId == i['val_id'] && attrId == i['attr_id']) {
            count = i['count'];
            return true;
          }
      })
    }

    return count;
  }
});
