import Component from '@ember/component';
import {computed, observer} from '@ember/object';
import EmberObject from '@ember/object';
import {A} from '@ember/array';

let Checkbox = EmberObject.extend({
  isSelected: computed('value', 'selection.[]', {
    get() {
      return this.get('selection').includes(this.get('value'));
    },

    set(_, checked) {
      let selection = this.get('selection');
      let selected = selection.includes(this.get('value'));
      let onchange = this.get('onchange');
      let updateSelectionValue = this.get('updateSelectionValue');
      let isMutable = typeof selection.addObject === 'function' && typeof selection.removeObject === 'function';

      // Dispatch onchange event to handler with updated selection if handler is specified
      if (onchange) {
        let updated = A(selection.slice());
        let operation;

        if (checked && !selected) {
          operation = 'added';
          updated.addObject(this.get('value'));
        } else if (!checked && selected) {
          operation = 'removed';
          updated.removeObject(this.get('value'));
        }

        onchange(updated, this.get('value'), operation);
      }

      // Mutate selection if updateSelectionValue is true and selection is mutable
      if (updateSelectionValue !== false && isMutable) {
        if (checked && !selected) {
          selection.addObject(this.get('value'));
        } else if (!checked && selected) {
          selection.removeObject(this.get('value'));
        }

        return checked;
      } else {

        // Only change the checked status of the checkbox when selection is mutated, because if
        // it is not mutated and the onchange handler does not update the bound selection value the
        // displayed checkboxes would be out of sync with bound selection value.
        return !checked;
      }
    }
  })
});

export default Component.extend({

  checkboxes: computed('options.[]', 'labelProperty', 'valueProperty', 'selection', function () {
    let labelProperty = this.get('labelProperty');
    let valueProperty = this.get('valueProperty');
    let selection = A(this.get('selection'));
    let onchange = this.get('onchange');
    let updateSelectionValue = this.get('updateSelectionValue') !== undefined ? this.get('updateSelectionValue') : true;
    let options = A(this.get('options'));

    let checkboxes = options.map((option) => {
      let label, value;

      if (labelProperty) {
        if (typeof option.get === 'function') {
          label = option.get(labelProperty);
        } else {
          label = option[labelProperty];
        }
      } else {
        label = String(option);
      }

      if (valueProperty) {
        if (typeof option.get === 'function') {
          value = option.get(valueProperty);
        } else {
          value = option[valueProperty];
        }
      } else {
        value = option;
      }

      return Checkbox.create({
        option: option,
        label: label,
        value: value,
        selection: selection,
        onchange: onchange,
        updateSelectionValue: updateSelectionValue
      });
    });

    return A(checkboxes);
  })
});
