<?php
/**
 * message HTML display helper
 */
class Zend_View_Helper_Projects
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

    public function projects()
    {   		
		// start a return value
		$return = "";

		// get all projects
		$projects = $this->view->projects;

		// loop through projects
		foreach ($projects as $key => $project) {	
			
			// condition : put in a new list?
			if ($key % 15 == 0) {
				$return .= '
						<ul class="clearfix">
				';
			}

			// condition : is thumb selected?
			$request = Zend_Controller_Front::getInstance()->getRequest();
			if (isset($request->slug) && $request->slug == $project->project_slug) {
				$selected = ' class="selected"';
			} else {
				$selected = '';
			}
			
			// add thumb
			$return .= '
							<li id="project-thumb-'.$key.'" class="project-thumb">
								<a href="/projects/'.$project->project_slug.'"'.$selected.'>
									<span></span>
									<img src="/_includes/uploads/'.$project->project_thumb.'" alt="'.$project->project_title.'" />
								</a>
							</li>
			';
			
			// condition : close list?
			if (($key > 0 && $key % 15 == 14) || $key == count($projects) - 1) {
				$return .= '
						</ul>
				';
			}			
		}
		
		
		return $return;
    }
}