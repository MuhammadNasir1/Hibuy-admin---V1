<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Hashids Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */
'connections' => [

    'main' => [
        'salt' => env('HASHIDS_SALT', 'hibuyo-secure-salt-2025'),
        'length' => 8, // minimum hash length
        // 'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
    ],

    'alternative' => [
        'salt' => 'another-salt-string',
        'length' => 6,
        // 'alphabet' => 'customalphabet123'
    ],

],


];
