<?php

return [

    'dsn' => env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN')),

    // capture release as git sha
    // 'release' => trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD')),

    'environment' => env('APP_ENV', 'production'),

    // When left empty or `null` the Laravel environment will be used
    'environment' => env('SENTRY_ENVIRONMENT'),

    // @see: https://docs.sentry.io/platforms/php/configuration/options/#browser-tracing
    'browser_tracing' => [
        'enabled' => env('SENTRY_BROWSER_TRACING_ENABLED', false),
    ],

    // @see: https://docs.sentry.io/platforms/php/configuration/options/#traces-sample-rate
    'traces_sample_rate' => env('SENTRY_TRACES_SAMPLE_RATE', 0.0),

    // @see: https://docs.sentry.io/platforms/php/guides/laravel/configuration/options/#profiles-sample-rate
    'profiles_sample_rate' => env('SENTRY_PROFILES_SAMPLE_RATE', 0.0),

    // @see: https://docs.sentry.io/platforms/php/configuration/options/#send-default-pii
    'send_default_pii' => env('SENTRY_SEND_DEFAULT_PII', false),

    // @see: https://docs.sentry.io/platforms/php/configuration/options/#default-integrations
    'default_integrations' => env('SENTRY_DEFAULT_INTEGRATIONS', true),

    // @see: https://docs.sentry.io/platforms/php/configuration/options/#before-send
    'before_send' => [env('SENTRY_BEFORE_SEND_CLASS'), 'beforeSend'],

    // @see: https://docs.sentry.io/platforms/php/configuration/options/#max-breadcrumbs
    'max_breadcrumbs' => env('SENTRY_MAX_BREADCRUMBS', 50),

    // @see: https://docs.sentry.io/platforms/php/configuration/options/#max-value-length
    'max_value_length' => env('SENTRY_MAX_VALUE_LENGTH', 500),

];



















