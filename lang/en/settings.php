<?php

$lang['url'] = 'The URL from which MathJax will be loaded.';
$lang['config'] = '(Optional) MathJax configuration; javascript code executed when MathJax loads.  See https://docs.mathjax.org/en/latest/options/';
$lang['configfile'] = '(Optional) One or more files containing MathJax configuration commands.  Specify paths relative to the dokuwiki installation directory (e.g. conf/mathjax.js or data/pages/mathjaxconf.txt), and separate multiple files with semicolons.';
$lang['mathtags'] = '(Optional) HTML tags to treat as math for MathJax to parse.  Specify each as the tag name without angle brackets, and separate multiple tags with commas. If using this option, the tags (with angle brackets) must be added to the MathJax configuration.  E.g. "mytag, othertag" here would need something like the following in the MathJax configuration: inlineMath: [ ["&lt;mytag>", "&lt;/mytag>"], ["&lt;othertag>", "&lt;/othertag>"] ]';

