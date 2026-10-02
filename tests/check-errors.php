<?php

declare(strict_types=1);

/*
 * Compares the PHPStan JSON report on stdin with the expected errors snapshot.
 * Messages differ between dependency versions, so only file, line and identifier are compared.
 * Pass --update to rewrite the snapshot.
 */

$snapshotFile = __DIR__ . '/expected-errors.json';
$fixtureDir = __DIR__ . '/Fixture/';

$report = json_decode((string) file_get_contents('php://stdin'), true, flags: JSON_THROW_ON_ERROR);
if (($report['errors'] ?? []) !== []) {
    fwrite(STDERR, 'PHPStan failed: ' . implode(PHP_EOL, $report['errors']) . PHP_EOL);
    exit(1);
}

$actual = [];
foreach ($report['files'] as $file => $result) {
    foreach ($result['messages'] as $message) {
        $actual[] = sprintf('%s:%d %s', str_replace($fixtureDir, '', $file), $message['line'], $message['identifier'] ?? 'unknown');
    }
}
sort($actual);

if (in_array('--update', $argv, true)) {
    $json = json_encode($actual, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    file_put_contents($snapshotFile, preg_replace('/^    /m', "\t", $json) . PHP_EOL);
    echo 'Snapshot updated.' . PHP_EOL;
    exit(0);
}

$expected = json_decode((string) file_get_contents($snapshotFile), true, flags: JSON_THROW_ON_ERROR);
$differences = static function (array $left, array $right): array {
    $remaining = array_count_values($right);
    $diff = [];
    foreach ($left as $error) {
        if (($remaining[$error] ?? 0) > 0) {
            $remaining[$error]--;
            continue;
        }
        $diff[] = $error;
    }

    return $diff;
};
$missing = $differences($expected, $actual);
$unexpected = $differences($actual, $expected);

if ($missing === [] && $unexpected === []) {
    echo sprintf('All %d expected errors reported.', count($expected)) . PHP_EOL;
    exit(0);
}

foreach ($missing as $error) {
    echo '- missing:    ' . $error . PHP_EOL;
}
foreach ($unexpected as $error) {
    echo '+ unexpected: ' . $error . PHP_EOL;
}
exit(1);
