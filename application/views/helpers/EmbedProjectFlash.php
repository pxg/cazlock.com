<?php
/**
 * Project Flash
 */
class Zend_View_Helper_EmbedProjectFlash
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

    public function embedProjectFlash($flash)
    {   		
		$content = '
		<div id="flash-'.$flash->id.'" class="flash">
			<p>You need Adobe Flash to be able to see this content.</p>
		</div>
		<script>
			var height = "'.$flash->height.'";
			var width = "'.$flash->width.'";
			var containerID = "flash-'.$flash->id.'"; 
			var params = {}; 
			params.wmode = "windowed";
			var flashVars = "";
			var attributes = {}; 
			attributes.id = containerID;
			swfobject.embedSWF("/_includes/uploads/'.$flash->flash_src.'", containerID, width, height, "10.0.0", "", flashVars, params, attributes);
		</script>
		';
		
		return $content;
    }
}