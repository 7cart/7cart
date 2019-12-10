import Component from '@ember/component';
import EmberObject, { computed } from '@ember/object';
import File from 'ember-file-upload/file';
import {observer} from '@ember/object';

export default Component.extend({
  init() {
    this._super(...arguments);
    this.setInitialState();
  },
  setInitialState(){
    this.set('model', EmberObject.create());
  },
  onUploadImage: observer('uploadImage', function () {
    if (!this.get('uploadImage')) {
      this.setInitialState();
    }
  }),
  _cropImg(file){
    file.name =  this.get('model.uploadImageOriginal.name') || 'no_name';
    file.readAsDataURL().then((url) => {
      this.set('model.croppedUrl', url);
    });
    this.set('uploadImage', file); //external property type ember-file-upload/file
  },
  actions: {
    crop(cropper) {
      let croppedImg = cropper.getCroppedCanvas({
        imageSmoothingQuality: 'high',
        imageSmoothingEnabled: true,
        maxWidth: 512,
        maxHeight: 512,
        fillColor: '#fff'
      }).toDataURL('image/jpeg', 1);
      this._cropImg(File.fromDataURL(croppedImg));
    },
    setAvatar(model, file) {
     // this.set('uploadImage', file);
      if (this.get('model.url')){
        this.set('model.croppedUrl', null);
        this.set('model.url', null);
      }

      this.set('model.uploadImageOriginal', file);
      // Set the URL so we can see a preview
      file.readAsDataURL().then((url) => {
        this.set('model.url', url);
      });
    }
  }
});
