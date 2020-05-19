<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// We are extending the core Profiler Class of CI
class MY_Profiler extends CI_Profiler
{
        public function __construct()
        {
                // Calling CI_Profiler constructor
                parent::__construct();
        }

        // Here we MUST override the original session data compiling function because it's private in CI_Profiler (I just copy/pasted it here without any updates)
        private function _compile_session_data()
        {
                if ( ! isset($this->CI->session))
                {
                        return;
                }

                $output = '<fieldset id="ci_profiler_csession" style="border:1px solid #000;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
                $output .= '<legend style="color:#000;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_session_data').'&nbsp;&nbsp;(<span style="cursor: pointer;" onclick="var s=document.getElementById(\'ci_profiler_session_data\').style;s.display=s.d​isplay==\'none\'?\'\':\'none\';this.innerHTML=this.innerHTML==\''.$this->CI->lang->line('profiler_section_show').'\'?\''.$this->CI->lang->line('profiler_section_hide').'\':\''.$this->CI->lang->line('profiler_section_show').'\';">'.$this->CI->lang->line('profiler_section_show').'</span>)</legend>';
                $output .= "<table style='width:100%;display:none' id='ci_profiler_session_data'>";

                foreach ($this->CI->session->all_userdata() as $key => $val)
                {
                        if (is_array($val) OR is_object($val))
                        {
                                $val = print_r($val, TRUE);
                        }

                        $output .= "<tr><td style='padding:5px; vertical-align: top;color:#900;background-color:#ddd;'>".$key."&nbsp;&nbsp;</td><td style='padding:5px; color:#000;background-color:#ddd;'>".htmlspecialchars($val)."</td></tr>\n";
                }

                $output .= '</table>';
                $output .= "</fieldset>";
                return $output;
        }

        // This function is the one we need to override to write output into a file instead of returning it to output (I just updated the return value here)
        public function run()
        {
                $output = "<div id='codeigniter_profiler' style='clear:both;background-color:#fff;padding:10px;'>";
                $fields_displayed = 0;

                foreach ($this->_available_sections as $section)
                {
                        if ($this->_compile_{$section} !== FALSE)
                        {
                                $func = "_compile_{$section}";
                                $output .= $this->{$func}();
                                $fields_displayed++;
                        }
                }

                if ($fields_displayed == 0)
                {
                        $output .= '<p style="border:1px solid #5a0099;padding:10px;margin:20px 0;background-color:#eee">'.$this->CI->lang->line('profiler_no_profiles').'</p>';
                }

                $output .= '</div>';

                // We try to write the output content to the file at www.yoursite.com/assets/xml/profiler.html (you should modify it to a directory existing in your site directory : take care, the path is relative to the index.php file of CI !)
                if(write_file('doc/debug/profiler.html', $output))
                        return; // It works, nothing to output (if you want to continue outputing profiler : return $output;)
                else
                        return "<script>alert('Writing profiler output failed.');</script>"; // It fails, echo a javascript alert to warn user
        }
} 
