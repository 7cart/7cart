import OAuth2PasswordGrant from 'ember-simple-auth/authenticators/oauth2-password-grant';
import config from 'front/config/environment';

export default OAuth2PasswordGrant.extend({
  serverTokenEndpoint: config.APP.backendHost+'/oauth/v2/token',
  clientId: config.APP.oAuth2Client,
  sendClientIdAsQueryParam: true,
  refreshAccessTokens: true,
  makeRequest(url, data, headers = {}) {
    data.client_secret = config.APP.oAuth2Secret;
    return this._super(url, data, headers);
  }
});
