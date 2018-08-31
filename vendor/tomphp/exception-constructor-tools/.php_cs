<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()->in(__DIR__);

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers([
        'unused_use',
        'new_with_braces',
        'phpdoc_scalar',
        'phpdoc_params',
        'multiline_array_trailing_comma',
        'phpdoc_trim',
        'return',
    ])
    ->setUsingCache(true)
    ->setUsingLinter(true)
    ->finder($finder)
;
