<?php

function xhprof_compute_self(array $xhprofRawData): array
{
    $xhprofSelfData = [];

    foreach ($xhprofRawData as $call => $data) {
        if ($call === "main()") {
            $parent = $call;
            if (!isset($xhprofSelfData[$parent])) {
                $xhprofSelfData[$parent] = ['ct' => 0, 'wt' => 0, 'wt_total' => 0];
            }
            $xhprofSelfData[$parent]['wt'] += $data['wt'];
            continue;
        }

        list ($parent, $child) = explode('==>', $call);

        if (!isset($xhprofSelfData[$parent])) {
            $xhprofSelfData[$parent] = ['ct' => 0, 'wt' => 0, 'wt_total' => 0];
        }

        if (!isset($xhprofSelfData[$child])) {
            $xhprofSelfData[$child] = ['ct' => 0, 'wt' => 0, 'wt_total' => 0];
        }

        $xhprofSelfData[$parent]['wt'] -= $data['wt'];
        $xhprofSelfData[$child]['wt'] += $data['wt'];
        $xhprofSelfData[$child]['wt_total'] += $data['wt'];
        $xhprofSelfData[$child]['ct'] += $data['ct'];
    }

    return $xhprofSelfData;
}

function print_calls($data, string $sortBy, int $count = 10)
{
    uasort($data, function ($a, $b) use ($sortBy) {
        return $b[$sortBy] <=> $a[$sortBy];
    });

    printf("SORT BY: %s\n", $sortBy);
    foreach (array_slice($data, 0, $count) as $call => $callInfo) {
        printf("%80s\twt_self=%d\twt_total=%d\tct=%d\n", $call, $callInfo['wt'], $callInfo['wt_total'], $callInfo['ct']);
    }
}

if (extension_loaded('tideways_xhprof')) {
    tideways_xhprof_enable();
    register_shutdown_function(function () {
        $data = tideways_xhprof_disable();
        $data = xhprof_compute_self($data);

        print_calls($data, 'wt_total', 10);
        print_calls($data, 'wt', 10);
    });
}
