7cart is a base project for building online shops, catalogs or service platforms. It's a new, better alternative to Shopify and Magento.

<img src="https://user-images.githubusercontent.com/3994818/52857920-51f9e180-3131-11e9-8be2-21e76f4d55ac.png" align="right" />

#### How is it different? 
The project is FREE OF
- spaghetti code,
- complicated database schema,

while being faster than the existing platforms.

#### How is this achieved?
The project DB schema doesn't implement [EAV][1],
but the [approach based on JSONB type][2],
what gives
* less relations in a database,
* simpler SQL queries,
  speed up with [GIN indexes][8]

#### Technology stack
Built as a Single Page Application (SPA),
it utilises the latest possible
* Ember.js
* Symfony 4
* PostgreSQL
* PHP 7

#### Features
* built in Docker,
it can be quickly deployed and run on different OSs;
* built-in Product Filter and Filer Counter

#### Getting started
 * [Installation][4]
 * [Documentation][5]

#### Feedback
We'd like to hear from you! Please, leave feature requests, bug reports or desire to contribute in the
[issue tracker](https://github.com/7cart/7cart/issues).

#### Help
1. check the [Troubleshooting][6] section;
2. raise an issue in the [issue tracker](https://github.com/7cart/7cart/issues).



[1]:https://en.wikipedia.org/wiki/Entity%E2%80%93attribute%E2%80%93value_model
[2]:https://coussej.github.io/2016/01/14/Replacing-EAV-with-JSONB-in-PostgreSQL/
[3]:https://github.com/7cart/7cart/wiki/Requirements
[4]:https://github.com/7cart/7cart/wiki/Installation
[5]:https://github.com/7cart/7cart/wiki
[6]:https://github.com/7cart/7cart/wiki/Troubleshooting
[7]:https://github.com/7cart/7cart/wiki/User-Guide
[8]:https://www.postgresql.org/docs/11/datatype-json.html#JSON-INDEXING
