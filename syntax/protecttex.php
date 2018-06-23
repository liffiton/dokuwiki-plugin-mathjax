<?php
/**
 * DokuWiki Plugin mathjax (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Mark Liffiton <liffiton@gmail.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

/**
 * Class syntax_plugin_mathjax_protecttex
 */
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

    /**
     * Syntax Type
     *
     * Needs to return one of the mode types defined in $PARSER_MODES in parser.php
     *
     * @return string
     */
    public function getType() {
        return 'protected';
    }

    /**
     * Sort for applying this mode
     *
     * @return int
     */
    public function getSort() {
        return 65;
    }

    /**
     * regexp patterns adapted from jsMath plugin: https://www.dokuwiki.org/plugin:jsmath
     *
     * @param string $mode
     */
    public function connectTo($mode) {
        $this->Lexer->addEntryPattern('(?<!\\\\)\$(?=[^\$][^\r\n]*?\$)',$mode,'plugin_mathjax_protecttex');
        $this->Lexer->addEntryPattern('\$\$(?=.*?\$\$)',$mode,'plugin_mathjax_protecttex');
        $this->Lexer->addEntryPattern('\\\\\((?=.*?\\\\\))',$mode,'plugin_mathjax_protecttex');
        $this->Lexer->addEntryPattern('\\\\\[(?=.*?\\\\])',$mode,'plugin_mathjax_protecttex');
        foreach (self::$ENVIRONMENTS as $env) {
            $this->Lexer->addEntryPattern('\\\\begin{' . $env . '}(?=.*?\\\\end{' . $env . '})',$mode,'plugin_mathjax_protecttex');
        }

        // Protect specified tags, if any
        $conf_mathtags = $this->getConf('mathtags');
        $mathtags = explode(',', $conf_mathtags);
        foreach ($mathtags as $tag) {
            $tag = trim($tag);
            if ($tag == "") { continue; }
            $this->Lexer->addEntryPattern('<' . $tag . '.*?>(?=.*?</' . $tag . '>)',$mode,'plugin_mathjax_protecttex');
        }
    }
    public function postConnect() {
        $this->Lexer->addExitPattern('\$(?!\$)','plugin_mathjax_protecttex');
        $this->Lexer->addExitPattern('\\\\\)','plugin_mathjax_protecttex');
        $this->Lexer->addExitPattern('\\\\\]','plugin_mathjax_protecttex');
        foreach (self::$ENVIRONMENTS as $env) {
            $this->Lexer->addExitPattern('\\\\end{' . $env . '}','plugin_mathjax_protecttex');
        }

        // Protect specified tags, if any
        $conf_mathtags = $this->getConf('mathtags');
        $mathtags = explode(',', $conf_mathtags);
        foreach ($mathtags as $tag) {
            $tag = trim($tag);
            if ($tag == "") { continue; }
            $this->Lexer->addExitPattern('</' . $tag . '>','plugin_mathjax_protecttex');
        }
    }

    /**
     * Handler to prepare matched data for the rendering process
     *
     * This function can only pass data to render() via its return value - render()
     * may be not be run during the object's current life.
     *
     * Usually you should only need the $match param.
     *
     * @param   string       $match   The text matched by the patterns
     * @param   int          $state   The lexer state for the match
     * @param   int          $pos     The character position of the matched text
     * @param   Doku_Handler $handler The Doku_Handler object
     * @return  array Return an array with all data you want to use in render
     */
    public function handle($match, $state, $pos, Doku_Handler $handler){

        $match = str_replace('<=>', '\Leftrightarrow', $match);
        $match = str_replace('<->', '\leftrightarrow', $match);
        $match = str_replace('->', '\rightarrow', $match);
        $match = str_replace('<-', '\leftarrow', $match);
        $match = str_replace('=>', '\Rightarrow', $match);
        $match = str_replace('<=', '\Leftarrow', $match);
        $match = str_replace('...', '\ldots', $match);
        $match = str_replace('−', '-', $match);

        // Just pass it through...
        return $match;
    }

    /**
     * Handles the actual output creation.
     *
     * @param   $mode     string        output format being rendered
     * @param   $renderer Doku_Renderer the current renderer object
     * @param   $data     array         data created by handler()
     * @return  boolean                 rendered correctly?
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode == 'xhtml' || $mode == 'odt') {
            /** @var Doku_Renderer_xhtml $renderer */

            // Just pass it through, but escape xml entities...
            $renderer->doc .= $renderer->_xmlEntities($data);
            return true;
        }
        if ($mode == 'latexport') {
            // Pass math expressions to latexport renderer
            $renderer->mathjax_content($data);	
            return true;
        }

        // For all other modes, pass through unchanged.
        $renderer->doc .= $data;
        return true;
    }
}

// vim:ts=4:sw=4:et:
