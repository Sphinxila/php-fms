<?php

namespace PHPFms;

class Log {
    /** @var Template\LogEngine */
    private static $engine = null;

    /**
     * Set engine
     * @param Template\LogEngine $engine
     */
    public static function SetLogEngine($engine)
    {
        self::$engine = $engine;
    }

    /**
     * @param string $message
     * @return bool
     */
    public static function Info(string $message): bool
    {
        if (!is_null(self::$engine))
            return self::$engine->info($message);
        return false;
    }

    /**
     * @param string $message
     * @return bool
     */
    public static function Error(string $message): bool
    {
        if (!is_null(self::$engine))
            return self::$engine->error($message);
        return false;
    }

    /**
     * Exception
     * @param \Exception $e
     * @return bool
     */
    public static function Exception($e): bool
    {
        $message = sprintf("[Error %d]%s", $e->getMessage(), $e->getCode());
        return self::Error($message);
    }
}