import { module, test } from 'qunit';
import { setupRenderingTest } from 'ember-qunit';
import { render } from '@ember/test-helpers';
import hbs from 'htmlbars-inline-precompile';

module('Integration | Component | elements/multi-select-checkboxes', function(hooks) {
  setupRenderingTest(hooks);

  test('it renders', async function(assert) {
    // Set any properties with this.set('myProperty', 'value');
    // Handle any actions with this.set('myAction', function(val) { ... });

    await render(hbs`{{elements/multi-select-checkboxes}}`);

    assert.equal(this.element.textContent.trim(), '');

    // Template block usage:
    await render(hbs`
      {{#elements/multi-select-checkboxes}}
        template block text
      {{/elements/multi-select-checkboxes}}
    `);

    assert.equal(this.element.textContent.trim(), 'template block text');
  });
});
