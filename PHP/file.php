<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Microlink
 *
 * You can't just ask customers what they want and then try to give that to them. 
 * By the time you get it built, they'll want something new.
 *
 * @package		Microlink
 * @author		1508 Dev Team
 * @copyright	Copyright (c) 2012-2099, microlink.im and other contributors
 * @license		http://microlink.im/license.html
 * @link		http://microlink.im/
 * @since		Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * Article Controller
 *
 * @package		Microlink
 * @subpackage	Controller
 * @category	Controller
 * @author		1508 Dev Team
 */
class File extends MK_Controller
{
	// Model path
	var $model_path = 'article/file_model';

	// Language pack path
	var $views_path = 'file';

	// Page path
	var $lang_path = 'article/file';

	// Page path
	var $page_path = 'article/file';

	// To identify the access permissions of the current page
	var $menu_uuid = 'cd15e70c-7fd0-11e1-a4e6-00155d001007';

	var $base = './uploads/';

	/**
	 * Default constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->model($this->model_path);
		$this->load->library(
				array('MK_Switch', 'MK_Tagger', 'MK_Category', 'Form_validation')
			);
		$this->load->helper('number');

		$path = $this->input->get('path');
		if ($path != '')
		{
			$path = $path . '/';
			$path = str_replace('//', '/', $path);
		}
		else
		{
			$path = '';
		}

		$string1 = realpath($this->base . $path);
		$string2 = realpath($this->base);

		if (strstr($string1, $string2) == false)
		{
			exit();
		}

		// Get popedom array
		$authority = $this->mk_users->get_popedom(
				$this->menu_uuid, $this->menu_authorize_query
			);
		if (!$authority['list'])
		{
			//** MK-ERROR-UNAUTHORIZED **/
			show_error($this->lang_common['error_unauthorized_page'], 500);
			exit();
		}

		//load language file
		$lang_current = $this->mk_integrate->get_lang($this->lang_path);

		if ($this->base . $path == $this->base)
		{
			$name = array_slice(scandir($this->base . $path), 2);
		}
		else
		{
			$name = array_slice(scandir($this->base . $path), 1);
		}

		$full_path = $this->base . $path;
		$info = array();
		
		foreach($name as $rows)
		{	
			$full = $this->base . $path . $rows;
			$time = fileatime($full);
			$info[$rows][0] = $rows;
			$info[$rows][1] = date('Y-m-d H:i:s', $time);
			$info[$rows][2] = byte_format(filesize($full));
			unset($full);
		}

		$data['page_path'] = $this->page_path;
		$data['lang_common'] = $this->lang_common;
		$data['lang_current'] = $lang_current;
		$data['menu_uuid'] = $this->menu_uuid;
		$data['authority'] = $authority;
		$data['file'] = $info;
		$data['full_path'] = $full_path;
		$data['base'] = $this->base;

		$this->load->view("{$this->views_path}/file", $data);
		unset($data);
	}

	//--------------------------------------------------------------------------'

	public function bulk_reco($mode = '')
	{
		if (!$this->mk_users->validate_popedom($this->menu_uuid, $this->menu_authorize_query, MK_LIMIT_DELETE))
		{
			//** MG-ERROR-UNAUTHORIZED **/
			exit('{"state": 0, "message": "' . $this->lang_common['prompt_unauthorized_operation'] . '"}');
		}

		$idlist = explode(',', $this->input->post()['idlist']);
		var_dump($idlist);

		switch (strtolower($mode))
		{
			case 'delete':
				foreach($idlist as $rows)
				{
					$string1 = realpath($rows);
					$string2 = realpath($this->base);

					if (strstr($string1, $string2) == false)
					{
						exit('{"state": 0, "message": "' . $this->lang_common['error_delete_dir'] . '"}');
					}

					if (is_dir("$rows") == false)
					{
						$result = unlink(realpath("$rows"));
						continue;
					}
					else
					{
						$result = $this->_del_dir("$rows");
						continue;
					}

					if ($result == false)
					{
						exit('{"state": 0, "message": "' . $this->lang_common['prompt_del_failure'] . '"}');
					}

					//** MG-EVENTLOG **/
					$this->mk_eventlog->write(
							MK_EVENT_LOG_MESSAGE, "Bulk delete file [ID:({'$rows'})]", $this->mk_users->Username
						);

					exit('{"state": 1, "message": "' . $this->lang_common['prompt_del_complete'] . '"}');
				}
				break;

			case 'move':
				var_dump($idlist);
				break;
		}
	}

	private function _del_dir($dir = '')
	{
		$dh=opendir($dir);
		while ($file=readdir($dh))
		{
	    	if($file!="." && $file!="..")
	    	{
	      		$fullpath=$dir."/".$file;
	      		if(!is_dir($fullpath))
	      		{
	          		unlink($fullpath);
	      		}
	      		else
	      		{
	          		$this->_del_dir($fullpath);
	      		}
	    	}
		}
		closedir($dh);
		//删除当前文件夹：
		if(rmdir($dir))
		{
	   		return true;
		}
		else
		{
	   		return false;
		}
	}
}
/* End of file file.php */
/* Location: ./applications/butler/controllers/article/file.php */