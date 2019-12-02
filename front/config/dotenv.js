/* eslint-env node */

'use strict';

const path = require('path');

module.exports = function(/* env */) {
  return {
    clientAllowedKeys: [
      'BACKEND_HOST',
      'BACKEND_NAMESPACE',
      'OAUTH_FACEBOOK_ID',
      'OAUTH_GOOGLE_ID',
      'OAUTH2_CLIENT_ID',
      'OAUTH2_CLIENT_SECRET'],
    fastbootAllowedKeys: [],
    failOnMissingKey: false,
    path: path.join(path.dirname(__dirname), '.env')
  }
};
