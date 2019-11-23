import Component from '@ember/component';
import {buildValidations, validator} from "ember-cp-validations";
import Object from '@ember/object';
import { inject } from '@ember/service';
import config from 'front/config/environment';

const Validations = buildValidations({
  email: {
    debounce: 200,
    validators: [
      validator('presence', true),
      validator('format', {
        type: 'email'
      })
    ]
  }
});

export default Component.extend(Validations,{
  ajax: inject(),
  init() {
    this._super(...arguments);
    this.set('didValidate', false);
    this.set('processing', false);
    this.set('errorMessage', '');
    this.set('successMessage', '');
  },
  actions: {
    reset() {
      this.validate().then(({model, validations}) => {
        if (!validations.get('isValid')) {
          this.set('didValidate', true);
          return;
        }

        this.set('processing', true);
        let url = config.APP.backendHost+ '/'+config.APP.backendNamespace+'/users/reset';
        return this.ajax.request(url, {
          method: 'POST',
          data: {
            'email': this.get('email')
          }
        }).then((reason) => {
          this.set('successMessage', reason);
          this.set('errorMessage', '');
        }).catch((reason) => {
          this.set('errorMessage', Object.create(reason).get('payload.error.message'));
          this.set('successMessage', '');
        }).finally(() => {
          this.set('processing', false);
        });
      });
    }
  }
});
