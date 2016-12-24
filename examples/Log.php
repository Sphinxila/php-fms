<?php

use PHPFms\Log;

class MyLog extends \PHPFms\Template\LogEngine {
    /** @var log file */
    private $logfile;

    /**
     * Constructor
     * Log constructor.
     */
    public function __construct()
    {
        $this->logfile = new \PHPFms\File("test.log", "a+");
    }

    /**
     * Error
     * @param string $message
     * @return bool
     */
    public function error(string $message): bool
    {
        $this->logfile->write($message . "\n");
    }

    /**
     * Info
     * @param string $message
     * @return bool
     */
    public function info(string $message): bool
    {
        $this->logfile->write($message . "\n");
    }
}

// Logger
$logger = new MyLog();

// Define log engine
Log::SetLogEngine($logger);

$dir = new \PHPFms\Directory("WEnoTeXisS");
$dir->writeFile("noFile.txt", "thisWilFail...");

