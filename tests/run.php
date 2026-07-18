<?php

declare(strict_types=1);

use InvalidArgumentException;
use NoviKey\Graph\BreadthFirstSearch;

require dirname(__DIR__) . '/src/BreadthFirstSearch.php';

$Graph = [
	'A' => ['B', 'C'],
	'B' => ['A', 'D'],
	'C' => ['A', 'D', 'E'],
	'D' => ['B', 'C', 'F'],
	'E' => ['C'],
	'F' => ['D'],
	'G' => [],
];

$Search = new BreadthFirstSearch($Graph);
$Checks = 0;

$AssertSame = static function (mixed $Expected, mixed $Actual, string $Message) use (&$Checks): void {
	$Checks++;

	if ($Expected !== $Actual) {
		fwrite(STDERR, sprintf(
			"FAIL: %s\nExpected: %s\nActual:   %s\n",
			$Message,
			var_export($Expected, true),
			var_export($Actual, true)
		));
		exit(1);
	}
};

$AssertSame(['A'], $Search->FindPath('A', 'A'), 'Start node is also the destination.');
$AssertSame(['A', 'B'], $Search->FindPath('A', 'B'), 'Direct connection is returned.');
$AssertSame(['A', 'B', 'D', 'F'], $Search->FindPath('A', 'F'), 'A shortest path is returned.');
$AssertSame([], $Search->FindPath('A', 'G'), 'Disconnected node returns an empty path.');
$AssertSame([], $Search->FindPath('UNKNOWN', 'A'), 'Unknown start node returns an empty path.');
$AssertSame([], $Search->FindPath('A', 'UNKNOWN'), 'Unknown destination returns an empty path.');
$AssertSame(true, $Search->HasPath('A', 'F'), 'HasPath detects an existing path.');
$AssertSame(false, $Search->HasPath('A', 'G'), 'HasPath detects a missing path.');

$ExceptionThrown = false;

try {
	new BreadthFirstSearch(['A' => ['B']]);
} catch (InvalidArgumentException) {
	$ExceptionThrown = true;
}

$AssertSame(true, $ExceptionThrown, 'Unknown neighbours are rejected.');

$ExceptionThrown = false;

try {
	new BreadthFirstSearch(['' => []]);
} catch (InvalidArgumentException) {
	$ExceptionThrown = true;
}

$AssertSame(true, $ExceptionThrown, 'Empty node names are rejected.');

echo sprintf("OK: %d checks passed.\n", $Checks);
