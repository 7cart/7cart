import Component from '@ember/component';

export default Component.extend({
  totalPages: 0,
  currentId: 0,
  pg(c, m) {
    let current = c,
      last = m,
      delta = 2,
      left = current - delta,
      right = current + delta + 1,
      range = [],
      rangeWithDots = [],
      l;

    for (let i = 1; i <= last; i++) {
      if (i == 1 || i == last || i >= left && i < right) {
        range.push(i);
      }
    }

    for (let i of range) {
      if (l) {
        if (i - l === 2) {
          rangeWithDots.push(l + 1);
        } else if (i - l !== 1) {
          rangeWithDots.push('...');
        }
      }
      rangeWithDots.push(i);
      l = i;
    }

    return rangeWithDots;
  },
  init() {
    this._super(...arguments);
    this.get('infinityModel').then(() => {
      this.pageDidChange();
      this.addObserver('infinityModel.content.currentPage', this, 'pageDidChange');
    });
  },
  pageDidChange() {
    this.get('infinityModel').then((im) => {
      this.set('totalPages', this.pg(im.currentPage, im._totalPages));
      this.set('currentId', im.currentPage);
    });
  },
  willDestroyElement() {
    this.removeObserver('infinityModel.content.currentPage', this, 'pageDidChange');
  },
  actions: {
    goto(pageNo) {
      pageNo = parseInt(pageNo);
      if (pageNo) {
        this.get('action')(pageNo);
      }
    }
  }
});
