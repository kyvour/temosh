<?php

namespace Temosh\Boot;

/**
 * Class BootLoader
 *
 * Initialize composer autoloader when cli is starting.
 */
class BootLoader
{

    /**
     * @var string
     *  The name of main app class. Required for autoloader checking.
     */
    protected $appClassName = \Temosh\Console\Shell::class;

    /**
     * Boot ups composer autoloader.
     *
     * @param $basePath
     *  The base path of application.
     */
    public static function boot($basePath) {
        $bootLoader = new static();

        $suggestions = $bootLoader->autoloaderSuggestions($basePath);
        $isAutoloadConnected = $bootLoader->connectAutoloader($suggestions);

        // Force exit if autoloader wasn't included.
        if (!$isAutoloadConnected) {
            $msg = '';
            $msg .= 'Unable to load autoload.php.' . PHP_EOL;
            $msg .= 'Run composer install to fetch dependencies and write autoload.php (http://docs.drush.org/en/master/install/).' . PHP_EOL;

            // Write error message to STDERR channel.
            $stderr = fopen('php://stderr', 'wb');
            if ($stderr !== false) {
                fwrite($stderr, $msg);
                fclose($stderr);
            }

            exit(1);
        }
    }

    /**
     * Includes composer autoloader.
     *
     * @param string[] $suggestions
     *  An array with possible composer autoload files.
     *
     * @return bool
     *  Returns true if autoloader was included and false otherwise.
     */
    protected function connectAutoloader($suggestions)
    {
        // Do nothing if autoloader was already included.
        if (class_exists($this->appClassName, false)) {
            return true;
        }

        // Try to include autoload.php generated by composer.
        foreach ($suggestions as $suggestion) {
            if (file_exists($suggestion)) {
                /** @var \Composer\Autoload\ClassLoader $classLoader */
                $classLoader = include $suggestion;

                // Make sure that autoloader works.
                if (
                    is_a($classLoader, \Composer\Autoload\ClassLoader::class) &&
                    $classLoader->loadClass($this->appClassName)
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns an array with possible composer autoload files.
     *
     * @param $basePath
     *  The base path of application.
     *
     * @return string[]
     */
    protected function autoloaderSuggestions($basePath)
    {
        return [
            // 'local' means that autoload.php is inside of Temosh.
            // That is, Temosh is its own Composer project.
            'local' => $basePath . '/vendor/autoload.php',
            // 'global' means autoload.php is outside of Temosh.
            // That is, Temosh is a dependency of a bigger project.
            'global' => $basePath . '/../../../vendor/autoload.php',
        ];
    }
}
