<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Exceptions\ReadableTrace;

use pointybeard\Helpers\Functions\Paths;

class ReadableTraceException extends \Exception implements Interfaces\ReadableTraceExceptionInterface
{
    public function __construct(string $message, $code = 0, \Exception $previous = null)
    {
        return parent::__construct(sprintf(
            "%s\r\n\r\nTrace\r\n==========\r\n%s\r\n",
            $message,
            $this->getReadableTrace()
        ), $code, $previous);
    }

    public function getReadableTrace(): ?string
    {
        // Nothing in the trace
        if (count($this->getTrace()) <= 0) {
            return null;
        }

        $traceLineFormat = '[%s:%d] %s%s%s();';

        $baseLine = [
            'relative' => null,
            'line' => null,
            'class' => null,
            'type' => null,
            'function' => null,
            'file' => null,
            'args' => [],
        ];

        $lines = [];

        foreach ($this->getTrace() as $line) {
            if (null !== $line['file']) {
                try {
                    $line['relative'] = Paths\get_relative_path(getcwd(), $line['file']);

                    // Something when wrong. Just use the full file path instead
                } catch (\Exception $ex) {
                    $line['relative'] = $line['file'];
                }
            }

            // This will keep values from $line but order them according to
            // $baseLine's array keys, otherwise the result from vsprintf will
            // be wonky
            $line = array_merge($baseLine, $line);

            $lines[] = vsprintf(
                $traceLineFormat,
                array_slice($line, 0, 5) // We don't care about 'file' or 'args'
            );
        }

        return implode($lines, PHP_EOL);
    }
}
