<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Kohana_Controller_Template_Fenom extends Controller_Template {

    public function before()
    {
        if ($this->auto_render === TRUE)
        {
            // Load the template
            $this->template = View_Fenom::factory($this->template);
        }
    }

}
