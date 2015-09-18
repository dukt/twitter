<?php

/*
 * Sami Documentation config
 *
*/

use Sami\Sami;
use Symfony\Component\Finder\Finder;
use Sami\Parser\Filter\TrueFilter;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('resources')
    ->exclude('tests')
    ->exclude('migrations')
    ->exclude('vendor')
    ->in(__DIR__.'/../Source');

$options = array(
    'theme'                => 'dukt',
    'title'                => 'Analytics Plugin for Craft CMS',
    'build_dir'            => __DIR__.'/build',
    'cache_dir'            => __DIR__.'/cache',
    'template_dirs'        => array(__DIR__.'/themes/'),
    'default_opened_level' => 2
);

$sami = new Sami($iterator, $options);

$sami['filter'] = function () {
    return new TrueFilter();
};

return $sami;