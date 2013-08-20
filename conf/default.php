<?php
/**
 * Default settings for the mathjax plugin
 *
 * @author Mark Liffiton <liffiton@gmail.com>
 */

$conf['url'] = 'https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS_HTML';
$conf['config'] = 'MathJax.Hub.Config({
    tex2jax: {
        inlineMath: [ ["$","$"], ["\\\\(","\\\\)"] ],
        displayMath: [ ["$$","$$"], ["\\\\[","\\\\]"] ],
        processEscapes: true
    }
});';
$conf['configfile'] = '';

