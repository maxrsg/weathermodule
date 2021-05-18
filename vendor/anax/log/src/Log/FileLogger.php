<?php

namespace Anax\Log;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

/**
 * Anax Logger class.
 *
 * The message MUST be a string or object implementing __toString().
 *
 * The message MAY contain placeholders in the form: {foo} where foo
 * will be replaced by the context data in key "foo".
 *
 * The context array can contain arbitrary data, the only assumption that
 * can be made by implementors is that if an Exception instance is given
 * to produce a stack trace, it MUST be in a key named "exception".
 *
 * See https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * for the full interface specification.
 */
class FileLogger extends AbstractLogger
{
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level   the loglevel to use for this entry.
     * @param string $message to log.
     * @param array  $context extra information to log.
     *
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        $file = ANAX_INSTALL_PATH . "/log/dev_log";
        $dateTime = new \DateTime("now", new \DateTimeZone("Europe/Stockholm"));
        $date = $dateTime->format("Y-m-d H:m:s e");

        $contextString = "";
        foreach ($context as $key => $val) {
            $contextString .= " $key=" . htmlentities($val) . ",";
        }
        $contextString = rtrim($contextString, ",");

        $record = "[$date] [$level] {$message}{$contextString}\n";
        file_put_contents($file, $record, FILE_APPEND);
    }
}
