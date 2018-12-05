# 7cart

This repository is an open source e-commerce platform. It may be used as an online store, online store platform, or service oriented platform.

It is built in a Docker container, thus it can be quickly deployed and run on a virtually any system.

The project database schema does not implement the traditional [EAV](https://en.wikipedia.org/wiki/Entity%E2%80%93attribute%E2%80%93value_model) DB pattern, but simpler and efficient [JSONB](https://coussej.github.io/2016/01/14/Replacing-EAV-with-JSONB-in-PostgreSQL/)

## Contributing

Contributions to 7cart are highly welcomed.
We encourage everyone to file feature requests and bug reports using the project's
[issue tracker](https://github.com/7cart/7cart/issues).

## Getting help
When having troubles with the deployment:
1. check the Troubleshooting section;
2. ask for help in chat with the team.

## Troubleshooting

Problem #1: `EACCES: permission denied, mkdir '/bitnami/mariadb'`

The solution was found [here](https://github.com/bitnami/bitnami-docker-mariadb/issues/136#issuecomment-354644226) and worked in an Arch-linux system:

1. Open `7cart/docker-compose.yml` file 
2. Insert `fix-postgres-permissions` block like this:
```
version: '2'

services:
........
  fix-postgres-permissions:
    image: 'bitnami/postgresql:9.6'
    user: root
    command: chown -R 1001:1001 /bitnami
    volumes:
    - ./data/postgres:/bitnami
    ........
``` 
 2. Add `depends_on` clause inside `postgresql` block like this:
``` 
  postgresql:
  .....
      depends_on:
      - fix-postgres-permissions
      ```
