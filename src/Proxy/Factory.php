<?php namespace Proxy;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Factory {

    /**
     * The default adapter name.
     *
     * @var string
     */
    protected static $defaultAdapter = 'guzzle';

    /**
     * Create a proxy instance.
     *
     * @param  string $adapter
     * @return Proxy\Proxy
     */
    public static function create($adapter = null)
    {
        $adapter = $adapter ?: static::getDefaultAdapter();

        $instance = static::createAdapter($adapter);

        return new Proxy($instance);
    }

    /**
     * Forward a request using the default adapter.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public static function forward(Request $request)
    {
        return self::create()->forward($request);
    }

    /**
     * Get the default adapter name.
     *
     * @return string
     */
    public static function getDefaultAdapter()
    {
        return static::$defaultAdapter;
    }

    /**
     * Set the default adapter name.
     *
     * @param string $adapter
     */
    public static function setDefaultAdapter($adapter)
    {
        static::$defaultAdapter = $adapter;
    }

    /**
     * Create an adapter instance based on the adapter name.
     *
     * @param  string $adapter
     * @return Proxy\Adapter
     */
    protected static function createAdapter($adapter)
    {
        $class = '\\Proxy\\Adapter\\' . ucfirst($adapter) . 'Adapter';

        if (class_exists($class))
        {
            return new $class;
        }

        throw new \InvalidArgumentException("Adapter [$adapter] not found.");
    }

}
