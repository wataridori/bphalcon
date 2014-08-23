<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => '', //Mysql username here
        'password'    => '', //Mysql password here
        'dbname'      => '', //Database name here
    ),
    'application' => array(
        'baseDir'        => __DIR__ . '/../../base/',
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'pluginsDir'     => __DIR__ . '/../../app/plugins/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'baseUri'        => '/', //base uri here. If you deploy the project with the url like example.com/exm, then the baseUri will be '/exm'
    )
));
