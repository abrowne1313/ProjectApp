<?php
require 'vendor/autoload.php';

use SebastianBergmann\CodeCoverage\Report\LCOV;

$coverage = unserialize(file_get_contents('coverage/coverage.cov'));
$writer = new LCOV;
$writer->process($coverage, 'coverage/lcov.info');
echo "LCOV generated at coverage/lcov.info\n";
