<?php

declare(strict_types=1);

namespace Fixture;

// A non-abstract class with `extends` makes ergebnis.testCaseWithSuffix look up PHPUnit's TestCase,
// which crashes PHPStan in projects without phpunit/phpunit.
final class Extending extends \RuntimeException {}
