<?php
/**
 * Tabs
 *
 */
if (!function_exists('tabs_shortcode')) {

    function tabs_shortcode($atts, $content = null) {
        $output = '<div class="tabs-wrapper">';
        $output .= '<ul class="nav nav-tabs">';
            
        //Create unique ID for this tab set
        $id = rand();

        //Build tab menu
        $numTabs = count($atts);

        for($i = 1; $i <= $numTabs; $i++){

            if($i==1) { 
                $addclass = "active";
            }       
                        
            $output .= '<li class="'.$addclass.'"><a href="#tab-'.$id.'-'.$i.'" data-toggle="tab">'.$atts['tab'.$i].'</a></li>';
                    
            $addclass = "";
        }

        $output .= '</ul>';
        $output .= '<div class="tab-content">';

        //Build content of tabs
        $i = 1;
        $tabContent = do_shortcode($content);
        $find = array();
        $replace = array();
        foreach($atts as $key => $value){
            if($i==1) { 
                $addclass = "in active";
            }   
            $find[] = '['.$key.']';
            $find[] = '[/'.$key.']';
            $replace[] = '<div id="tab-'.$id.'-'.$i.'" class="tab-pane fade '.$addclass.'">';
            $replace[] = '</div><!-- .tab (end) -->';
            $i++;
            $addclass = "";
        }

        $tabContent = str_replace($find, $replace, $tabContent);

        $output .= $tabContent;

        $output .= '</div><!-- .tab-content (end) -->';
        $output .= '</div><!-- .tabs-wrapper (end) -->';

        return $output;

    }
    add_shortcode('tabs', 'tabs_shortcode');

}?>