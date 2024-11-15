<?php



namespace Composer\Autoload;

class ComposerStaticInit7b9e105d12024270cb1f9386de9f25b4
{
    public static $prefixLengthsPsr4 = array (
        'V' =>
        array (
            'Velstack\\Pusher\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Velstack\\Pusher\\' =>
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7b9e105d12024270cb1f9386de9f25b4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7b9e105d12024270cb1f9386de9f25b4::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7b9e105d12024270cb1f9386de9f25b4::$classMap;

        }, null, ClassLoader::class);
    }
}
