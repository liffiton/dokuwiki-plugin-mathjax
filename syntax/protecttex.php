<?php
/**
 * DokuWiki Plugin mathjax (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Mark Liffiton <liffiton@gmail.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'syntax.php';

class syntax_plugin_mathjax_protecttex extends DokuWiki_Syntax_Plugin {
    # We need to grab any math before dokuwiki tries to parse it.
    # Once it's 'claimed' by this plugin (type: protected), it won't be altered.

    # Set of environments that this plugin will protect from Dokuwiki parsing
    # * is escaped to work in regexp below
    # Note: "math", "displaymath", and "flalign" environments seem to not be 
    #        recognized by Mathjax...  They will still be protected from Dokuwiki,
    #        but they will not be rendered by MathJax.
    private static $ENVIRONMENTS = array(
        "math",
        "displaymath",
        "equation",
        "equation\*",
        "eqnarray",
        "eqnarray\*",
        "align",
        "align\*",
        "flalign",
        "flalign\*",
        "alignat",
        "alignat\*",
        "multline",
        "multline\*",
        "gather",
        "gather\*",
    );

    public function getType() {
        return 'protected';
    }

    public function getSort() {
        return 65;
    }

    // regexp patterns adapted from jsMath plugin: http://www.dokuwiki.org/plugin:jsmath
    public function connectTo($mode) {
        $this->Lexer->addEntryPattern('(?<!\\\\)\$(?=[^\$][^\r\n]*?\$)',$mode,'plugin_mathjax_protecttex');
        $this->Lexer->addEntryPattern('\$\$(?=.*?\$\$)',$mode,'plugin_mathjax_protecttex');
        $this->Lexer->addEntryPattern('\\\\\((?=.*?\\\\\))',$mode,'plugin_mathjax_protecttex');
        $this->Lexer->addEntryPattern('\\\\\[(?=.*?\\\\])',$mode,'plugin_mathjax_protecttex');
        foreach (self::$ENVIRONMENTS as $env) {
            $this->Lexer->addEntryPattern('\\\\begin{' . $env . '}(?=.*?\\\\end{' . $env . '})',$mode,'plugin_mathjax_protecttex');
        }
    }
    public function postConnect() {
        $this->Lexer->addExitPattern('\$(?!\$)','plugin_mathjax_protecttex');
        $this->Lexer->addExitPattern('\\\\\)','plugin_mathjax_protecttex');
        $this->Lexer->addExitPattern('\\\\\]','plugin_mathjax_protecttex');
        foreach (self::$ENVIRONMENTS as $env) {
            $this->Lexer->addExitPattern('\\\\end{' . $env . '}','plugin_mathjax_protecttex');
        }
    }

    public function handle($match, $state, $pos, &$handler){
        // Just pass it through...
        return $match;
    }

    public function render($mode, &$renderer, $data) {
        if($mode != 'xhtml') return false;

        // Just pass it through, but escape xml entities...
        $renderer->doc .= $renderer->_xmlEntities($data);

        return true;
    }
}

// vim:ts=4:sw=4:et:
