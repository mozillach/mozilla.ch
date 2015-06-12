# mozilla.ch
This is the official repository for the mozilla.ch website.

## Local Setup

1. Install composer: https://getcomposer.org/download/
2. Run ```php composer.phar install```
3. Start server: ```php app/console server:run ```

### API Keys
For the Mozillian faces you need a mozillians API key. You can generate one [here](https://mozillians.org/en-US/apikeys/). Set the key as the value for `mozillians.api_key` in [app/config/parameters.yml](/app/config/parameters.yml).

All other used APIs don't need a key for the used scopes.
