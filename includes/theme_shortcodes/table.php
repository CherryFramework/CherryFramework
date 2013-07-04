<?php
/**
 * Table
 *
 */
if (!function_exists('table_shortcode')) {

    function table_shortcode($atts, $content = null) {
        $output = '<table class="table table-bordered table-striped">';

        //Build thead
        $output .= '<thead><tr>';
        $total = count($atts);

        for($i = 1; $i <= $total; $i++){                    
            $output .= '<th>'.$atts['td'.$i].'</th>';
        }

        $output .= '</tr></thead>';
        $output .= '<tbody><tr>';

        //Build content of table
        $tableContent = do_shortcode($content);
        $find = array();
        $replace = array();

        foreach($atts as $key => $value){

            $find[] = '['.$key.']';
            $find[] = '[/'.$key.']';
            $replace[] = '<td>';
            $replace[] = '</td>';
        }

        $tableContent = str_replace($find, $replace, $tableContent);
        $temp = trim($tableContent);
        $tableArray = explode("</td>", $temp);

        $i = 1;
        for ($key=0; $key < count($tableArray) - 1; $key++) {

            if ($i > $total) {
                $i = 1;
                $output .= '<tr>'; 
            }

            $output .= $tableArray[$key];

            if ($i % $total == 0) {
                $output .= '</tr>';
            }

            $i++;
        }

        $output .= '</tbody>';
        $output .= '</table><!-- table (end) -->';

        return $output;

    }
    add_shortcode('table', 'table_shortcode');

}?>