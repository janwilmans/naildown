<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6b4ce4626fb1210f415d495938769b1e
{
    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'cebe\\markdown\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'cebe\\markdown\\' => 
        array (
            0 => __DIR__ . '/..' . '/cebe/markdown',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6b4ce4626fb1210f415d495938769b1e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6b4ce4626fb1210f415d495938769b1e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
