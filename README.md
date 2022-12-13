<div align="center">

<img src="resource/img/logo/logo.png">

**WhatMeDo** is a simple symfony ToDo list.

[Features](#features) •
[Requirement](#requirement) •
[Installation](#installation) •
[Configuration](#configuration)

</div>




## Features

* **Easy** - Easy to learn and use, friendly construction.

* **Docker** - Installation via docker.

* **Free** - You can use it anywhere, whatever you want.

## Requirement

Only [Docker](https://docs.docker.com/get-docker/) and [Docker-Compose](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-compose-on-ubuntu-20-04)  🤤

## Installation
### 1) Up Docker
#### 1.1) Build base image
``$ make build-base``<br> 
#### 1.2) Build app image
``$ make build-app``<br>
#### 1.3) Create and start containers
``$ make up``<br>
### 2) Enter to app
``$ make enter``
### 3) Migrate database
``$ bin/console doctrine:migrations:migrate``
### 4) Create user
### For admin
``$ bin/console app:create:admin <email> <password>``
### For default user
``$ bin/console app:create:user <email> <password>``

### Go to - www.your.url/