# Small task for the guys of AbcHosting

## Requirements

- Have [git](https://git-scm.com/downloads) installed
- Have [Docker](https://docs.docker.com/install) and [docker-compose](https://docs.docker.com/compose/install) installed

## Installation

First, you'll need to clone this code from the repository. Use the following command

``` bash
git clone https://github.com/SrChach/task_abchosting.git
```

Then, go to the root of the project and use this command for setup the environment

``` bash
docker-compose up
```

When command finishes, go to `localhost:8082`. Project must be running there.

## Usage

Once installed, the project can be used logging-in to the system by using the users: test, test1, test2, test3, test4, test5.

All of them with password 'test'

## Description

This small project was a challenge from a company named 'Abc hosting ltd'.

Was an small e-commerce car.
Rules:

- Just PHP, no PHP frameworks
- Just use JS libraries
- All by an http API, not render all the page
- All things made by my own hand
- Small time to deliver
- List products
- Rate products. Products can only be rated if buyed before
- Initial quantity $100, reduced each time a buy is done
- Only can buy if a transport type is selected

I could've done it better, but... this is my project