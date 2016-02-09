<?php

$parameters = [
    // ENV_VAR_NAME => symfony parameter name
    'MOZILLIANS_API_KEY' => 'mozillians.api_key',
];

foreach ($parameters as $envVar => $sfParam) {
    $value = getenv($envVar);
    if ($value !== false && $value !== '') {
        $container->setParameter($sfParam, $value);
    }
}
