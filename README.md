# mozilla.ch

This is the official repository for the mozilla.ch website.

## Local Setup

1. Install composer: https://getcomposer.org/download/
2. Run ```php composer.phar install```
3. Start server: ```php app/console server:run ```

### API Keys
For the Mozillian faces you need a mozillians API key. You can generate one [here](https://mozillians.org/en-US/apikeys/). Set the key as the value for `mozillians.api_key` in [app/config/parameters.yml](/app/config/parameters.yml) (doesn't exist in the repo, it's generated with composer). Alternatively you can set the environment variable `MOZILLIANS_KEY`.

All other used APIs don't need a key for the used scopes.

## Docker Container

### Building the Docker Container
Make sure you have docker installed.

Run `docker build -t mozillach/mozilla.ch` to build the docker container.

### Releasing a New Docker Container
[![Travis CI Builds](https://travis-ci.org/mozillach/mozilla.ch.svg?branch=release)](https://travis-ci.org/mozillach/mozilla.ch)

The deployed docker container is based on the release branch and built with Travis CI.

### Running the Container
Replace `{mozillians API key}` with a mozillians API key for the v2 API with public access privileges. See [API Keys]{#api-keys} for how to get one.

Run `docker run -e MOZILLIANS_KEY={mozillians API key} mozillach/mozilla.ch` to start provisioning and then start apache.

To run it in a deployment situation use `docker run -h mozilla.ch -p 80:80 -d -e "MOZILLAINS_KEY={mozillians API key}" mozillach/mozilla.ch`.
