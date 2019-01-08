import Component from '@ember/component';
import {inject as service} from '@ember/service';

export default Component.extend({
  loadText: 'Load more',
  loadedText: 'Loaded',
  infinity: service(),
  actions:{
    loadMore() {
      this.get('infinityModel').then((im) => {
        this.infinity.infinityLoad(im);
      });
    }
  }
});
