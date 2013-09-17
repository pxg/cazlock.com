<?php
/**
 * message HTML display helper
 */
class Zend_View_Helper_Footer
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function scriptPath($script)
    {
        return $this->view->getScriptPath($script);
    }

    public function footer()
    {   		
		$content = '
		<div id="footer">
			<p>&copy; Caz Lock '.date("Y").'</p>
		</div>
		';
		
		return $content;
    }
}