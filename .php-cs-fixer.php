<?php
$header = <<<EOF
@author Mygento Team
@copyright 2023 Mygento (https://www.mygento.com)
@package Mygento_AdminInactivity
EOF;

$finder = PhpCsFixer\Finder::create()
    ->in('.')
    ->ignoreVCSIgnored(true);

$config = new \Mygento\CS\Config\Module($header);
$config->setFinder($finder);
return $config;
