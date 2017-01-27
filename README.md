# mozilla.ch

This is the official repository for the mozilla.ch website.

## Local Setup

1. Install composer: https://getcomposer.org/download/
2. Install bower: http://bower.io/
3. Run `php composer.phar install`
4. Start server: `php app/console server:run`

### API Keys
For the Mozillian faces you need a mozillians API key. You can generate one [here](https://mozillians.org/en-US/apikeys/). Set the key as the value for `mozillians.api_key` in [app/config/parameters.yml](/app/config/parameters.yml) (doesn't exist in the repo, it's generated with composer). Alternatively you can set the environment variable `MOZILLIANS_KEY`.

All other used APIs don't need a key for the used scopes.

## Docker Container

### Running the Container (Development, very experimental)
Install [docker-compose](https://docs.docker.com/compose/install/) if not already included in your docker build. Now open ```/etc/hosts``` and add

```
127.0.0.1   mozilla.ch
```

Now you can run the command below. Replace `{mozillians API key}` with a mozillians API key for the v2 API with public access privileges. See [API Keys](#api-keys) for how to get one.

```
MOZILLIANS_KEY={mozillians API key} docker-compose up
```

to start the container. The website is now available through `mozilla.ch` and you can just reload that page to see your changes. Do not forget to remove the ```/etc/hosts``` entry after you're done.

**Current problems:**
* It seems that we are running in production mode since we start the normal shell script. This has a lot of caching.
* Eliminate the second step with /etc/hosts. It would be great to have this on localhost:8000 or similar.

### Building the Docker Container
Make sure you have docker installed.

Run `docker build -t mozillach/mozilla.ch .` to build the docker container.

### Releasing a New Docker Container
[![Travis CI Builds](https://travis-ci.org/mozillach/mozilla.ch.svg?branch=release)](https://travis-ci.org/mozillach/mozilla.ch)

The deployed docker container is based on the release branch and built directly on docker hub. To release this container to the productive website, contact the Community IT team for now (#communityit on irc.mozilla.org).

### Running the Container (Production)
Replace `{mozillians API key}` with a mozillians API key for the v2 API with public access privileges. See [API Keys](#api-keys) for how to get one.

Run `docker run -e MOZILLIANS_KEY={mozillians API key} mozillach/mozilla.ch` to start provisioning and then start apache.
