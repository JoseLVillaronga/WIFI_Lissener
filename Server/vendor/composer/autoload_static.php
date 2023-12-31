<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1667501256670a622404927db69f14ee
{
    public static $files = array (
        'decc78cc4436b1292c6c0d151b19445c' => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpseclib\\' => 10,
        ),
        'R' => 
        array (
            'RouterOS\\' => 9,
        ),
        'D' => 
        array (
            'DivineOmega\\SSHConnection\\' => 26,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpseclib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib',
        ),
        'RouterOS\\' => 
        array (
            0 => __DIR__ . '/..' . '/evilfreelancer/routeros-api-php/src',
        ),
        'DivineOmega\\SSHConnection\\' => 
        array (
            0 => __DIR__ . '/..' . '/divineomega/php-ssh-connection/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1667501256670a622404927db69f14ee::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1667501256670a622404927db69f14ee::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
