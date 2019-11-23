import Component from '@ember/component';
import {buildValidations, validator} from "ember-cp-validations";
import Object from '@ember/object';
import { inject } from '@ember/service';
import config from 'front/config/environment';

const Validations = buildValidations({
  password: {
    debounce: 200,
    validators: [
      validator('presence', true),
      validator('length', { min: 6 }),
      validator('format', {
        regex: /^(?=.*\d)(?=.*[a-zA-Z]).*$/,
        message: 'Password must include at least one letter and number'
      })
    ]
  },
  confirmPassword: validator('confirmation', {
    on: 'password'
  })
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
    change() {
      this.validate().then(({model, validations}) => {
        if (!validations.get('isValid')) {
          this.set('didValidate', true);
          return;
        }

        this.set('processing', true);
        let url = config.APP.backendHost+ '/'+config.APP.backendNamespace+'/users/change';
        return this.ajax.request(url, {
          method: 'POST',
          data: {
            'token': this.get('token'),
            'password': this.get('password')
          }
        }).then(() => {
          this.set('successMessage', 'You password successfully changed');
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
