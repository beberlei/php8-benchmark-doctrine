<?php

if (extension_loaded('tideways_xhprof')) {
    tideways_xhprof_enable();
    register_shutdown_function(function () {
        $data = tideways_xhprof_disable();
        print_canonical($data);
    });

    function print_canonical($xhprof_data)
    {
        if (!is_array($xhprof_data)) {
            throw new \UnexpectedValueException("print_canonical expects an array, but %s given.", gettype($xhprof_data));
        }

        // some functions are not part of the trace
        // due to being an opcode in php7, these
        // need to be ignored for a common test-output
        $ignoreFunctions = array('strlen', 'is_array');

        ksort($xhprof_data);
        foreach($xhprof_data as $func => $metrics) {
            foreach ($ignoreFunctions as $ignoreFunction) {
                if (strpos($func, "==>" . $ignoreFunction) !== false) {
                    continue 2;
                }
            }

            echo str_pad($func, 40) . ":";
            ksort($metrics);
            foreach ($metrics as $name => $value) {

                // Only call counts are stable.
                // Wild card everything else. We still print
                // the metric name to ensure it was collected.
                if (!in_array($name, array("ct"))) {
                    $value = "*";
                } else {
                    $value = str_pad($value, 8, " ", STR_PAD_LEFT);
                }

                echo " {$name}={$value};";
            }
            echo "\n";
        }
    }
}
