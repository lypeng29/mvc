<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitec17dadfa04a535b8aaa16fde3d04a28
{
    public static $prefixLengthsPsr4 = array (
        'Q' => 
        array (
            'QL\\' => 3,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'QL\\' => 
        array (
            0 => __DIR__ . '/..' . '/jaeger/querylist',
        ),
    );

    public static $classMap = array (
        'Callback' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
        'CallbackBody' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
        'CallbackParam' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
        'CallbackParameterToReference' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
        'CallbackReturnReference' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
        'CallbackReturnValue' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
        'DOMDocumentWrapper' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
        'DOMEvent' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
        'ICallbackNamed' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
        'phpQuery' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
        'phpQueryEvents' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
        'phpQueryObject' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
        'phpQueryPlugins' => __DIR__ . '/..' . '/jaeger/phpquery-single/phpQuery.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitec17dadfa04a535b8aaa16fde3d04a28::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitec17dadfa04a535b8aaa16fde3d04a28::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitec17dadfa04a535b8aaa16fde3d04a28::$classMap;

        }, null, ClassLoader::class);
    }
}