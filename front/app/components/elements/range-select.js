import Component from '@ember/component';

export default Component.extend({
  init() {
    this._super(...arguments);
    let values = this.get('selectedFilter.'+this.get('name'));
    if (values){
      this.set('from', values.min || 0);
      this.set('to', values.max || '');
    } else {
      this.set('from', 0);
      this.set('to', '');
    }
  },
  actions: {
    execute() {
      this.get('onchange')(this.get('name'), {"min": this.get('from'), "max": this.get('to')});
    }
  }
})
