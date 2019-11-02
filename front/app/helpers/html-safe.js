import Helper from '@ember/component/helper';
import { htmlSafe } from '@ember/template';

export default Helper.extend({
  compute(data) {
    htmlSafe(data);
  }
});
