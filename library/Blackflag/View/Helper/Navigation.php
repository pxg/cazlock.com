<?php
/**
 * Blackflag navigation helper.  Is required so that we can use our own navigation
 * helpers within the 'Navigation' sub directory by setting the 'NS' constant.
 * @see http://n4.nabble.com/Zend-Navigation-visible-in-sitemap-not-in-menu-td658632.html
 */

/**
 * @see Zend_View_Helper_Navigation.php
 */
require_once 'Zend/View/Helper/Navigation.php';

/**
 * Proxy helper for retrieving navigational helpers and forwarding calls
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Blackflag_View_Helper_Navigation
    extends Zend_View_Helper_Navigation
{
    /**
     * View helper namespace
     *
     * @var string
     */
    const NS = 'Blackflag_View_Helper_Navigation';

    /**
     * Returns the helper matching $proxy
     *
     * The helper must implement the interface
     * {@link Zend_View_Helper_Navigation_Helper}.
     *
     * @param string $proxy                        helper name
     * @param bool   $strict                       [optional] whether
     *                                             exceptions should be
     *                                             thrown if something goes
     *                                             wrong. Default is true.
     * @return Zend_View_Helper_Navigation_Helper  helper instance
     * @throws Zend_Loader_PluginLoader_Exception  if $strict is true and
     *                                             helper cannot be found
     * @throws Zend_View_Exception                 if $strict is true and
     *                                             helper does not implement
     *                                             the specified interface
     */
    public function findHelper($proxy, $strict = true)
    {
        if (isset($this->_helpers[$proxy])) {
            return $this->_helpers[$proxy];
        }

        if (!$this->view->getPluginLoader('helper')->getPaths(self::NS)) {
            $this->view->addHelperPath(
                    str_replace('_', '/', self::NS),
                    self::NS);
        }

        if ($strict) {
            $helper = $this->view->getHelper($proxy);
        } else {
            try {
                $helper = $this->view->getHelper($proxy);
            } catch (Zend_Loader_PluginLoader_Exception $e) {
                return null;
            }
        }

        if (!$helper instanceof Zend_View_Helper_Navigation_Helper) {
            if ($strict) {
                require_once 'Zend/View/Exception.php';
                $e = new Zend_View_Exception(sprintf(
                        'Proxy helper "%s" is not an instance of ' .
                        'Zend_View_Helper_Navigation_Helper',
                        get_class($helper)));
                $e->setView($this->view);
                throw $e;
            }

            return null;
        }

        $this->_inject($helper);
        $this->_helpers[$proxy] = $helper;

        return $helper;
    }
}
