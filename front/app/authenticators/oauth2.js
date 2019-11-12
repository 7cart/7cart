import OAuth2PasswordGrant from 'ember-simple-auth/authenticators/oauth2-password-grant';

import RSVP from 'rsvp';
import { isEmpty } from '@ember/utils';
import { run } from '@ember/runloop';
import { A, makeArray } from '@ember/array';
import { warn } from '@ember/debug';
import {
  keys as emberKeys,
  merge,
  assign as emberAssign
} from '@ember/polyfills';

import isFastBoot from 'ember-simple-auth/utils/is-fastboot';
import {inject as service} from '@ember/service';
const assign = emberAssign || merge;
const keys = Object.keys || emberKeys; // Ember.keys deprecated in 1.13

export default OAuth2PasswordGrant.extend({
  fastboot: service('fastboot'),
  serverTokenEndpoint: '//localhost:8000/oauth/v2/token',
  clientId: '1_673n1g24ei8884w8wg4wcwwg4gkw488k0gg0wgoskscsc4sgk4',
  sendClientIdAsQueryParam: true,
  refreshAccessTokens: true,
  get tokenRefreshOffset() {
    const min = 5;
    const max = 10;

    return 1;
  },
 // clientSecret: 'shsed4mdzj4g0kkk84c8s4ogkkg08gg4kkskkw4skocc48g4o',
  makeRequest(url, data, headers = {}) {
    data.client_secret = 'shsed4mdzj4g0kkk84c8s4ogkkg08gg4kkskkw4skocc48g4o';
    return this._super(url, data, headers);
  },
  authenticate(identification, password, scope = [], headers = {}) {
    return new RSVP.Promise((resolve, reject) => {
      const data = { 'grant_type': 'password', username: identification, password };
      const serverTokenEndpoint = this.get('serverTokenEndpoint');

      const scopesString = makeArray(scope).join(' ');
      if (!isEmpty(scopesString)) {
        data.scope = scopesString;
      }
      this.makeRequest(serverTokenEndpoint, data, headers).then((response) => {
        run(() => {
          if (!this._validate(response)) {
            reject('access_token is missing in server response');
          }

          const expiresAt = this._absolutizeExpirationTime(response['expires_in']);
          console.log('---->', expiresAt);
          this._scheduleAccessTokenRefresh(response['expires_in'], expiresAt, response['refresh_token']);
          if (!isEmpty(expiresAt)) {
            response = assign(response, { 'expires_at': expiresAt });
          }

          resolve(response);
        });
      }, (response) => {
        run(null, reject, response);
      });
    });
   },

  _scheduleAccessTokenRefresh(expiresIn, expiresAt, refreshToken) {
    const refreshAccessTokens = this.get('refreshAccessTokens') ;//&& !isFastBoot();
    console.log('@@',!isFastBoot(),isFastBoot());
    if (refreshAccessTokens) {
      const now = (new Date()).getTime();
      if (isEmpty(expiresAt) && !isEmpty(expiresIn)) {
        expiresAt = new Date(now + expiresIn * 1000).getTime();
      }
      const offset = this.get('tokenRefreshOffset');
      console.log('^^2', expiresAt, offset);
      if (!isEmpty(refreshToken) && !isEmpty(expiresAt) && expiresAt > now - offset) {
        run.cancel(this._refreshTokenTimeout);
        delete this._refreshTokenTimeout;
        if (!Ember.testing) {
          this._refreshTokenTimeout = run.later(this, this._refreshAccessToken, expiresIn, refreshToken, expiresAt - now - offset);
        }
      }
    }
  },
});
