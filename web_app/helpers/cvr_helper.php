<?php

if ( ! function_exists('script_tag'))
{
    /** Script
    * Generates a script inclusion of a JavaScript file Based on the CodeIgniters original Link Tag.
    * Author(s): Isern Palaus <ipalaus@ipalaus.es>
    *            David Mulder <david@greatslovakia.com>
    * @access    public
    * @param    mixed    javascript sources or an array
    * @param    string    language
    * @param    string    type
    * @param    boolean    should index_page be added to the javascript path
    * @return    string
    */
    function script_tag($src = '', $language = 'javascript', $type = 'text/javascript', $index_page = FALSE)
    {
        $CI =& get_instance();

        $script = '<script';

        if (is_array($src))
        {
            foreach ($src as $k=>$v)
            {
                if ($k == 'src' AND strpos($v, '://') === FALSE)
                {
                    if ($index_page === TRUE)
                    {
                        $script .= ' src="'.$CI->config->site_url($v).'"';
                    }
                    else
                    {
                        $script .= ' src="'.$CI->config->slash_item('base_url').$v.'"';
                    }
                }
                else
                {
                    $script .= "$k=\"$v\"";
                }
            }
            
            $script .= "></script>\n";
        }
        else
        {
            if ( strpos($src, '://') !== FALSE)
            {
                $script .= ' src="'.$src.'" ';
            }
            elseif ($index_page === TRUE)
            {
                $script .= ' src="'.$CI->config->site_url($src).'" ';
            }
            else
            {
                $script .= ' src="'.$CI->config->slash_item('base_url').$src.'" ';
            }
                
            $script .= 'language="'.$language.'" type="'.$type.'"';
            
            $script .= ' /></script>'."\n";
        }

    
        return $script;
    }
  
}
 
// ------------------------------------------------------------------------

if ( ! function_exists('title_page'))
{
	/**
	 * Generates title tag
	 *	 
	 * @param	string
	 * @return	string
	 */
	function title_page($tit = "")
	{
		return '<title>'.$tit.'</title>';
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('input_crv'))
{
	/**
	 * Generates title tag
	 *	 
	 * @param	array
	 * @return	string
	 */
	function inputText_crv($param = array())
	{        
        $propsInput = array('name' => $param['inputName'], 'id' => $param['inputName']);
        if (array_key_exists('inputType'  , $param)) { $propsInput['type'       ] = $param['inputType'];    }
        if (array_key_exists('class'      , $param)) { $propsInput['class'      ] = $param['class'];        }
        if (array_key_exists('placeholder', $param)) { $propsInput['placeholder'] = $param['placeholder'];  }
        if (array_key_exists('value'      , $param)) { $propsInput['value'      ] = $param['value'];        }
        if (array_key_exists('maxlength'  , $param)) { $propsInput['maxlength'  ] = $param['maxlength'];    }
        
        $script  = '<section class="col '.(array_key_exists('col', $param)?$param['col']:"col-11").'">';
        $script .= '     <label class="label">'. (array_key_exists('label', $param)?$param['label']:"").'</label>';
        $script .= '     <label class="input"><i class="icon-append fa '. (array_key_exists('icon', $param)?$param['icon']:"fa-building").'"></i>';
        $script .=         form_input($propsInput);
        $script .=  (array_key_exists('tooltip', $param)?'<b class="tooltip tooltip-bottom-right">'.$param['tooltip'].'</b>':"");
        $script .='  </label>
                    </section>';
        
        return $script;
	}
}


function dropdown_crv($result_array, $data = '', $options = array(), $selected = array(), $extra = '')
{
    $myOptions = array();
    foreach ($result_array as $key => $value){
        $myOptions[$value['id']] = $value['value'];
    }
    return form_dropdown($data, $myOptions, $selected, $extra );
}

