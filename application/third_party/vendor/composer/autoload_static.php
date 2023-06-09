<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9c5182f7684c3740b7aa04516da10985
{
    public static $prefixesPsr0 = array (
        'M' => 
        array (
            'Monolog' => 
            array (
                0 => __DIR__ . '/..' . '/monolog/monolog/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit9c5182f7684c3740b7aa04516da10985::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit9c5182f7684c3740b7aa04516da10985::$classMap;

        }, null, ClassLoader::class);
    }
}
