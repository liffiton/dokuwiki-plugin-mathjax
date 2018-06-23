<?php
/**
 * DokuWiki Plugin mathjax (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Mark Liffiton <liffiton@gmail.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

/**
 * Add scripts via an event handler
 */
class action_plugin_mathjax_enable extends DokuWiki_Action_Plugin {

    /**
     * Registers our handler for the TPL_METAHEADER_OUTPUT event
     */
    public function register(Doku_Event_Handler $controller) {
       $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'handle_tpl_metaheader_output');
    }

    /**
     * Add <script> blocks to the headers:
     *   - One to load MathJax and one to configure it
     *   - Also add one block per configfile, if any are specified
     * See https://docs.mathjax.org/en/latest/configuration.html#using-in-line-configuration-options
     *
     * @param Doku_Event $event
     * @param            $param
     */
    public function handle_tpl_metaheader_output(Doku_Event &$event, $param) {
        // Create main config block
        $event->data['script'][] = array(
			'type'    => 'text/x-mathjax-config',
			'_data'   => $this->getConf('config'),
		);

        // Include config files, if any specified
        $configfiles = $this->getConf('configfile');
        $files = explode(';', $configfiles);
        foreach ($files as $f) {
            $f = trim($f);
            if ($f == "" or !is_readable($f)) {
                continue;
            }
            $contents = file_get_contents(DOKU_INC . $f);
            if ($contents) {
                $event->data['script'][] = array(
                    'type'    => 'text/x-mathjax-config',
                    '_data'   => "\n// " . $f . "\n" . $contents,
                );
            }
        }

        // Load MathJax itself
        $event->data['script'][] = array(
			'type'    => 'text/javascript',
			'charset' => 'utf-8',
			'src'     => $this->getConf('url'),
			'_data'   => '',
		);
    }

}

// vim:ts=4:sw=4:et:
