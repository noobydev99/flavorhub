<?php
namespace FlavorHub\Integration;

/**
 * Logger Utility (Integration Layer)
 * Logs application events and errors to a file.
 */
class Logger {
    private string $logFile;

    public function __construct(string $logFile = __DIR__ . '/../../logs/app.log') {
        $this->logFile = $logFile;
        
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    /**
     * Write a log message.
     */
    public function log(string $level, string $message): void {
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = sprintf("[%s] [%s]: %s\n", $timestamp, strtoupper($level), $message);
        file_put_contents($this->logFile, $formattedMessage, FILE_APPEND);
    }

    public function info(string $message): void {
        $this->log('INFO', $message);
    }

    public function warning(string $message): void {
        $this->log('WARNING', $message);
    }

    public function error(string $message): void {
        $this->log('ERROR', $message);
    }
}
