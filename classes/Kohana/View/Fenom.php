<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Kohana_View_Fenom extends View {

    protected $_fenom_options = 0;

    public static function factory($file = NULL, array $data = NULL, $options = 0)
    {
        return new View_Fenom($file, $data, $options);
    }

    protected static function capture($kohana_view_filename, array $kohana_view_data, $options = 0)
    {
        $config = Kohana::$config->load('kofenom');
        return Fenom::factory(
            $config['templates_dir'],
            $config['compiled_dir'],
            $options + $config['options']
        )->fetch($kohana_view_filename, array_merge($kohana_view_data, View::$_global_data));
    }

    public function __construct($file = NULL, array $data = NULL, $options = 0)
    {
        if ($file !== NULL)
        {
            $this->set_filename($file);
        }

        if ($data !== NULL)
        {
            $this->_data = $data + $this->_data;
        }

        if ($options)
        {
            $this->_fenom_options = $options;
        }
    }

    public function set_filename($file)
    {
        $config = Kohana::$config->load('kofenom');

        if ( ! isset($config['ext']))
        {
            $config['ext'] = 'tpl';
        }

        if (($path = Kohana::find_file('views', $file, $config['ext'])) === FALSE)
        {
            throw new View_Fenom_Exception('The requested view :file could not be found', array(
                ':file' => $file,
            ));
        }

        $this->_file = $file.'.'.$config['ext'];

        return $this;
    }

    public function render($file = NULL)
    {
        if ($file !== NULL)
        {
            $this->set_filename($file);
        }

        if (empty($this->_file))
        {
            throw new View_Fenom_Exception('You must set the file to use within your view before rendering');
        }

        return View_Fenom::capture($this->_file, $this->_data, $this->_fenom_options);
    }

}
