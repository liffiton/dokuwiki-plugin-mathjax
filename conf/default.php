<?php
/**
 * Default settings for the mathjax plugin
 *
 * @author Mark Liffiton <liffiton@gmail.com>
 */

$conf['url'] = 'https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js';
$conf['config'] = 'MathJax = {
    tex: {
        inlineMath: [ ["$","$"], ["\\\\(","\\\\)"] ],
        displayMath: [ ["$$","$$"], ["\\\\[","\\\\]"] ],
        processEscapes: true
    },
    svg: {
        fontCache: "global"
    }
};';
$conf['configfile'] = '';
$conf['mathtags'] = '';

