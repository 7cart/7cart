import Controller from '@ember/controller';
import {inject as service} from '@ember/service';
import {computed} from '@ember/object';
import Object from '@ember/object';
import config from 'front/config/environment';

export default Controller.extend({
  session: service(),
  isDirty: computed('uploadImage', 'model.hasDirtyAttributes', function () {
    return (this.get('uploadImage') || this.get('model.hasDirtyAttributes')) ? true : false;
  }),
  actions: {
    async submit() {
      this.set('errorMessage', '');
      let file = this.get('uploadImage');
      if (file) {
        let access_token = this.get('session.data.authenticated.access_token');
        let url = config.APP.backendHost+ '/'+config.APP.backendNamespace+'/users/upload';
        await file.upload(url, {
          headers: {Authorization: `Bearer ${access_token}`}
        });
      }

      this.get('model').save().catch((reason) => {
        this.set('errorMessage', Object.create(reason).get('errors.0.title'));
      }).finally(() => {
        this.set('uploadImage', null);
      })
    }
  }
});
