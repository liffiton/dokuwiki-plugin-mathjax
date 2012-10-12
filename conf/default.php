<?php
/**
 * Default settings for the mathjax plugin
 *
 * @author Mark Liffiton <liffiton@gmail.com>
 */

$conf['url'] = 'http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML';
$conf['config'] = 'MathJax.Hub.Config({
    tex2jax: {
        inlineMath: [ ["$","$"], ["\\\\(","\\\\)"],["<jsm>","</jsm>"] ],
        displayMath: [ ["$$","$$"], ["\\\\[","\\\\]"],["<jsmath>","</jsmath>"] ],
        processEscapes: true
    }
});';

