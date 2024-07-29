<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitda5a899885233319a792876913e44336
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Mamad\\Projets\\' => 14,
        ),
        'E' => 
        array (
            'Enumeration\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Mamad\\Projets\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Enumeration\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Enumeration',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitda5a899885233319a792876913e44336::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitda5a899885233319a792876913e44336::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitda5a899885233319a792876913e44336::$classMap;

        }, null, ClassLoader::class);
    }
}
