import Component from '@ember/component';
import {observer} from '@ember/object';

export default Component.extend({
  _init() {
    this.set('from', this.get('selection.min') || 0);
    this.set('to', this.get('selection.max'));
  },
  onSelectionChange: observer('selection', function() {
   this._init();
  }),
  init() {
    this._super(...arguments);
    this._init();
  },
  actions: {
    execute() {
      this.get('onchange')({"min": this.get('from'), "max": this.get('to')});
    }
  }
})
