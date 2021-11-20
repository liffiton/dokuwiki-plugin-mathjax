<?php
/**
 * Default settings for the mathjax plugin
 *
 * @author Mark Liffiton <liffiton@gmail.com>
 */

$conf['url'] = 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.9/MathJax.js?config=TeX-AMS_CHTML.js';
$conf['config'] = 'MathJax.Hub.Config({
    tex2jax: {
        inlineMath: [ ["$","$"], ["\\\\(","\\\\)"] ],
        displayMath: [ ["$$","$$"], ["\\\\[","\\\\]"] ],
        processEscapes: true
    }
});';
$conf['configfile'] = '';
$conf['asciimath'] = 0;
$conf['mathtags'] = '';

