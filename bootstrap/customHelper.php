<?php

/**
 * Configures a tenant's database connection.

 * @param  string $tenantName The database name.
 * @return void
 */
function configureConnection($connectionName=NULL)
{
    // Just get access to the config. 
    $config = App::make('config');

    // Will contain the array of connections that appear in our database config file.
    $connections = $config->get('database.connections');

    // This line pulls out the default connection by key (by default it's `mysql`)
    $defaultConnection = $connections[$config->get('database.default')];

    // Now we simply copy the default connection information to our new connection.
    $newConnection = $defaultConnection;
    
    // Override the database name.
    $newConnection['database'] = $connectionName;
    
    // This will add our new connection to the run-time configuration for the duration of the request.
    App::make('config')->set('database.connections.'.$connectionName, $newConnection);
}
?>