<?php

declare(strict_types=1);

namespace pointybeard\Helpers\Exceptions\ReadableTrace;

use pointybeard\Helpers\Functions\Paths;
use pointybeard\Helpers\Functions\Strings;

class ReadableTraceException extends \Exception implements Interfaces\ReadableTraceExceptionInterface
{
    public function getReadableTrace(string $format = '[{{PATH}}/{{FILENAME}}:{{LINE}}] {{CLASS}}{{TYPE}}{{FUNCTION}}();'): ?string
    {
        // Nothing in the trace
        if (count($this->getTrace()) <= 0) {
            return null;
        }

        $base = [
            'path' => null,
            'filename' => null,
            'line' => null,
            'class' => null,
            'type' => null,
            'function' => null,
            'file' => null,
            'args' => [],
        ];

        // Set up the placeholder array (remove last 2 items, get the array keys
        // and make each one upper case)
        $placeholders = array_map('strtoupper', array_keys(array_slice($base, 0, count($base) - 2)));

        $lines = [];

        foreach ($this->getTrace() as $line) {
            if (null !== $line['file']) {
                try {
                    $line['filename'] = basename($line['file']);
                    $line['path'] = dirname(Paths\get_relative_path(getcwd(), $line['file']));

                    // Something when wrong. Just use the full file path instead
                } catch (\Exception $ex) {
                    $line['path'] = $line['file'];
                }
            }

            // This will keep values from $line but order them according to
            // array keys of $base, otherwise the result from vsprintf() will
            // be wonky
            $line = array_merge($base, $line);

            // Now remove the last two items (file and args) since we don't need
            // to worry about them
            $line = array_slice($line, 0, count($line) - 2);

            // Replace the placeholder vaules in $format to produce the final
            // line
            $lines[] = Strings\replace_placeholders_in_string(
                $placeholders,
                array_values($line),
                $format,
                true
            );
        }

        return implode($lines, PHP_EOL);
    }
}
