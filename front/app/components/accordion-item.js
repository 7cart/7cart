import BsAccordionItem from 'ember-bootstrap/components/bs-accordion/item';

export default BsAccordionItem.extend({
  collapsed: false,
  onClick() {
    this.toggleProperty('collapsed');
  }
});
