7cart is a base project for building online shops, catalogs or service platforms. 7cart is written with simple code and simple database schema. It is easy to support and fast.

#### Play with live demo!
[Live store front-end page][9]

[Live store admin page][10]

:star: Star us on GitHub — it helps!

<img src="https://user-images.githubusercontent.com/3994818/52857920-51f9e180-3131-11e9-8be2-21e76f4d55ac.png" align="right" />

#### Try it!
 * [Installation][4]
 * [Documentation][5]

#### Why 7cart?
7cart is for building projects that can't be built with the existing e-commerce platforms. It aims to be free of:
* difficult to support code and database
* slow requests

#### How is 7cart better?
The project database doesn't implement [EAV][1] table,
but the Entity table with [JSONB][8] columns.
That gives:
* less relations and simple code
* simpler and faster SQL queries [[proof link][2]]

#### Features
* built in Docker,
it can be quickly deployed and run on different OSs;
* built-in Product Filters and Filer Counters

#### Technology stack
Built as a Single Page Application (SPA),
it utilises the latest possible
* Ember.js
* Symfony 4
* PostgreSQL
* PHP 7
 
#### Download
[Download Zip with project source code.](https://github.com/7cart/7cart/archive/master.zip)

#### Tell us what you think!
Please, leave your thoughts in the
[issue tracker](https://github.com/7cart/7cart/issues)

or message to [7cart Facebook page]( https://www.facebook.com/7cart)



[1]:https://en.wikipedia.org/wiki/Entity%E2%80%93attribute%E2%80%93value_model
[2]:https://coussej.github.io/2016/01/14/Replacing-EAV-with-JSONB-in-PostgreSQL/
[3]:https://github.com/7cart/7cart/wiki/Requirements
[4]:https://github.com/7cart/7cart/wiki/Installation
[5]:https://github.com/7cart/7cart/wiki
[6]:https://github.com/7cart/7cart/wiki/Troubleshooting
[7]:https://github.com/7cart/7cart/wiki/User-Guide
[8]:https://www.postgresql.org/docs/11/datatype-json.html
[9]:http://35.204.41.32:4200/category/1
[10]:http://35.204.41.32:8000/admin
