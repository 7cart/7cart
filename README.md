7cart is an open source project that may be furnished for use as an online store or a service oriented platform.

It uses the concept of nodes that possess or reference categories, attributes and attribute values. This gives the project a wide application. For more explanation see [User Guide][7]


#### Technology stack
Built as a Single Page Application (SPA) it utilises the latest
* Ember.js
* Symfony
* PostgreSQL

<img src="https://user-images.githubusercontent.com/3994818/52748847-4357e100-2ff0-11e9-82bd-7f3b9bd101b8.png" alt="7cart admin screen" align="right" />

#### Features
* built in a Docker container, thus it can be quickly deployed and run on any system Docker supports;
* the project database schema does not implement traditional [EAV][1] DB pattern, but a simpler [approach based on JSONB][2]
* built-in Sidebare filter and filer counter
* lean and mean code: uncompressed repo size 1.6 MB, memory consumption when idle: 14 MB back-end, and 11.8 MB front-end

#### Getting started
 * [Requirements][3]
 * [Documentation][5]

#### Contributing
Contributions to 7cart are highly welcomed.
We encourage everyone to file feature requests and bug reports using the project's
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


