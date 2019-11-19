import Component from '@ember/component';
import {inject as service} from '@ember/service';
import {observer} from '@ember/object';
import {validator, buildValidations} from 'ember-cp-validations';
import Object from '@ember/object';

const Validations = buildValidations({
  name: {
    debounce: 500,
    validators: [
      validator('presence', true)
    ]
  },
  email: {
    debounce: 200,
    validators: [
      validator('presence', true),
      validator('ds-error'),
      validator('format', {
        type: 'email'
      })
    ]
  },
  plainPassword: {
    debounce: 200,
    validators: [
      validator('presence', true),
      validator('length', { min: 6 }),
      validator('format', {
        regex: /^(?=.*\d)(?=.*[a-zA-Z]).*$/,
        message: 'Password must include at least one letter and number'
      })
    ]
  }
});

export default Component.extend(Validations, {
  store: service(),
  session: service(),
  init() {
    this._super(...arguments);
    this.set('didValidate', false);
    this.set('processing', false);
  },
  emailObserves: observer('email', function () {
    this.set('errors', null);
  }),
  actions: {
    registration() {
      this.validate().then(({model, validations}) => {
        if (!validations.get('isValid')) {
          this.set('didValidate', true);
          return;
        }

        let user = this.get('store').createRecord('user', this.getProperties('name', 'email', 'plainPassword'));

        this.set('processing', true);
        user.save().then((_user) => {
          this.get('session').authenticate('authenticator:oauth2', _user.get('email'), user.get('plainPassword')).catch((reason) => {
            this.set('errorMessage', Object.create(reason).get('responseJSON.error_description'));
          }).finally(() => {
            this.set('processing', false);
          });
        }).catch((errors) => {
          this.set('processing', false);
          this.set('errors', user.get('errors'));
          user.rollbackAttributes();
        })
      });
    }
  }
});
