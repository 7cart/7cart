7cart is a base project for building things like online stores, catalogs, service platforms and so on.

It uses the concept of nodes that possess or reference categories, attributes and attribute values. This gives the project a wide application. See our [User Guide][7]

<img src="https://user-images.githubusercontent.com/3994818/52857920-51f9e180-3131-11e9-8be2-21e76f4d55ac.png" align="right" />

#### Technology stack
Built as a Single Page Application (SPA) it utilises the latest possible
* Ember.js
* Symfony 4
* PostgreSQL
* PHP 7

#### Features
* built in Docker,
it can be quickly deployed and run on virtually any system;
* the project DB schema does not implement [EAV][1],
but the [approach based on JSONB][2], what gives:
    * simpler and faster SQL queries
    * compact, easy support code
* built-in Product Filter and Filer Counter

#### Getting started
 * [Requirements][3]
 * [Documentation][5]

#### Contributing
We encourage to file feature requests, bug reports or desire to contribute using the project's
[issue tracker](https://github.com/7cart/7cart/issues).

#### Getting help
1. check the [Troubleshooting][6] section;
2. raise an issue.


[1]:https://en.wikipedia.org/wiki/Entity%E2%80%93attribute%E2%80%93value_model
[2]:https://coussej.github.io/2016/01/14/Replacing-EAV-with-JSONB-in-PostgreSQL/
[3]:https://github.com/7cart/7cart/wiki/Requirements
[4]:https://github.com/7cart/7cart/wiki/Installation
[5]:https://github.com/7cart/7cart/wiki
[6]:https://github.com/7cart/7cart/wiki/Troubleshooting
[7]:https://github.com/7cart/7cart/wiki/User-Guide


