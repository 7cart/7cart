/* eslint-env node */

'use strict';

const path = require('path');

module.exports = function(/* env */) {
  return {
    clientAllowedKeys: ['BACKEND_HOST', 'BACKEND_NAMESPACE', 'OAUTH_FACEBOOK_ID', 'OAUTH_GOOGLE_ID'],
    fastbootAllowedKeys: [],
    failOnMissingKey: false,
    path: path.join(path.dirname(__dirname), '.env')
  }
};
