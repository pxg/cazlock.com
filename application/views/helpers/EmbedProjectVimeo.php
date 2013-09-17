<?php
/**
 * Project Flash
 */
class Zend_View_Helper_EmbedProjectVimeo
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

    public function embedProjectVimeo($vimeo)
    {   		
		$content = '
		<div id="vimeo-'.$vimeo->id.'" class="vimeo">
			<object width="'.$vimeo->width.'" height="'.$vimeo->height.'">
				<param name="allowfullscreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id='.$vimeo->vimeo_url.'&amp;server=vimeo.com&amp;fullscreen=1" />
				<embed src="http://vimeo.com/moogaloop.swf?clip_id='.$vimeo->vimeo_url.'&amp;server=vimeo.com&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="'.$vimeo->width.'" height="'.$vimeo->height.'"></embed>
			</object>
		</div>
		';
		
		return $content;
    }
}