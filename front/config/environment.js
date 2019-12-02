'use strict';

module.exports = function(environment) {
  let ENV = {
    modulePrefix: 'front',
    environment,
    rootURL: '/',
    locationType: 'auto',
    EmberENV: {
      FEATURES: {
        // Here you can enable experimental features on an ember canary build
        // e.g. 'with-controller': true
      },
      EXTEND_PROTOTYPES: {
        // Prevent Ember Data from overriding Date.parse.
        Date: false
      }
    },

    torii: {
      disableRedirectInitializer: true,
      sessionServiceName: 'session-torii',
      providers: {
        'facebook-oauth2': {
          apiKey: process.env.OAUTH_FACEBOOK_ID,
          redirectUri: process.env.FRONT_HOST+'/torii/redirect.html',
          scope: 'email'
        },
        'google-oauth2': {
          apiKey: process.env.OAUTH_GOOGLE_ID,
          redirectUri: process.env.FRONT_HOST+'/torii/redirect.html',
          scope: 'email profile'
        }
      }
    },

    'ember-simple-auth': {
      authenticationRoute: 'login',
      routeAfterAuthentication: 'application',
      routeIfAlreadyAuthenticated: 'application'
    },


    APP: {
      backendHost: process.env.BACKEND_HOST,
      backendNamespace: process.env.BACKEND_NAMESPACE,
      backendDockerHost: 'http://nginx:8000', //inside Docker
      oAuth2Client: process.env.OAUTH2_CLIENT_ID,
      oAuth2Secret: process.env.OAUTH2_CLIENT_SECRET
      // Here you can pass flags/options to your application instance
      // when it is created
    },
    fastboot: {
      hostWhitelist: [/^127.0.0.1:\d+$/, /^localhost:\d+$/, /^localhost$/]
    }
  };

  if (environment === 'development') {
    ENV.APP.backendHost = process.env.BACKEND_HOST;
    ENV.APP.backendNamespace =  process.env.BACKEND_NAMESPACE;
    // ENV.APP.LOG_RESOLVER = true;
    // ENV.APP.LOG_ACTIVE_GENERATION = true;
    // ENV.APP.LOG_TRANSITIONS = true;
    // ENV.APP.LOG_TRANSITIONS_INTERNAL = true;
    // ENV.APP.LOG_VIEW_LOOKUPS = true;
  }

  if (environment === 'test') {
    // Testem prefers this...
    ENV.locationType = 'none';

    // keep test console output quieter
    ENV.APP.LOG_ACTIVE_GENERATION = false;
    ENV.APP.LOG_VIEW_LOOKUPS = false;

    ENV.APP.rootElement = '#ember-testing';
    ENV.APP.autoboot = false;
  }

  if (environment === 'production') {
    // here you can enable a production-specific feature
  }

  return ENV;
};
