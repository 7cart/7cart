import EmberRouter from '@ember/routing/router';
import config from './config/environment';

const Router = EmberRouter.extend({
  location: config.locationType,
  rootURL: config.rootURL
});

Router.map(function() {
  this.route('category', { path: '/category/:id/' }, function () {
    this.route('filter', {path: '/:filter/'});
  });
});

export default Router;
