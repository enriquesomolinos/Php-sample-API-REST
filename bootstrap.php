<?php
if( !file_exists( dirname( __DIR__ ) . '/composer.lock' ) )
{
    die( "Dependencies must be installed using composer:\n\nphp composer.phar install --dev\n\n"
        . "See http://getcomposer.org for help with installing composer\n" );
}

$autoloader = require dirname( __DIR__ ) . '/vendor/autoload.php';