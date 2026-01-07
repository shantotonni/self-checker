<?php

/*
|--------------------------------------------------------------------------
| Outlet Database Configuration
|--------------------------------------------------------------------------
|
| This file contains the default database credentials for connecting
| to outlet databases. Each outlet has its own database server and
| we need default credentials to connect to them.
|
| Add these to your .env file:
|
| OUTLET_DB_NAME=your_database_name
| OUTLET_DB_PORT=1433
| OUTLET_DB_USER=sa
| OUTLET_DB_PASSWORD=your_password
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Name
    |--------------------------------------------------------------------------
    |
    | The default database name to use when connecting to outlet servers.
    | This can be overridden per configuration.
    |
    */
    'database_name' => env('OUTLET_DB_NAME', 'outlet_db'),

    /*
    |--------------------------------------------------------------------------
    | Default Database Port
    |--------------------------------------------------------------------------
    |
    | The default port for MSSQL connections. Usually 1433 for SQL Server.
    |
    */
    'database_port' => env('OUTLET_DB_PORT', '1433'),

    /*
    |--------------------------------------------------------------------------
    | Default Database Username
    |--------------------------------------------------------------------------
    |
    | The default username for connecting to outlet databases.
    |
    */
    'database_user' => env('OUTLET_DB_USER', 'sa'),

    /*
    |--------------------------------------------------------------------------
    | Default Database Password
    |--------------------------------------------------------------------------
    |
    | The default password for connecting to outlet databases.
    | Make sure to keep this secure!
    |
    */
    'database_password' => env('OUTLET_DB_PASSWORD', ''),

    /*
    |--------------------------------------------------------------------------
    | Connection Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout in seconds for database connections.
    |
    */
    'connection_timeout' => env('OUTLET_DB_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Encryption
    |--------------------------------------------------------------------------
    |
    | Whether to use encrypted connections. Set to 'yes' for production.
    |
    */
    'encrypt' => env('OUTLET_DB_ENCRYPT', 'no'),

];
