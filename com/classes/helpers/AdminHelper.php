<?php
namespace ydn;

class AdminHelper {
    public static function getFormattedDate($date) {

        return '';
        $date = strtotime($date);
        $month = date('F', $date);
        $year = date('Y', $date);

        return $month.' '.$year;
    }

    public static function test() {
        return '';
    }
    
    public static function getAllDefaultSettings() {
    	$defaults = array();
	    $defaults['textDecorationTypes'] = array(
	    	'underline' => __('Underline', YDN_TEXT_DOMAIN),
	    	'overline' => __('Overline', YDN_TEXT_DOMAIN),
	    	'line-through' => __('Line Through', YDN_TEXT_DOMAIN),
	    	'none' => __('None', YDN_TEXT_DOMAIN)
	    );
	    
    	return apply_filters('ydnDefaults', $defaults);
    }

    public static function createAttrs($attrs) {
        $attrString = '';
        if(!empty($attrs) && isset($attrs)) {

            foreach ($attrs as $attrName => $attrValue) {
                $attrString .= ''.$attrName.'="'.$attrValue.'" ';
            }
        }

        return $attrString;
    }

    public static function createSelectBox($data, $selectedValue, $attrs) {
        $selected = '';
        $attrString = self::createAttrs($attrs);

        $selectBox = '<select '.$attrString.'>';

        foreach($data as $value => $label) {

            /*When is multiselect*/
            if(is_array($selectedValue)) {
                $isSelected = in_array($value, $selectedValue);
                if($isSelected) {
                    $selected = 'selected';
                }
            }
            else if($selectedValue == $value) {
                $selected = 'selected';
            }
            else if(is_array($value) && in_array($selectedValue, $value)) {
                $selected = 'selected';
            }

            $selectBox .= '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';
            $selected = '';
        }

        $selectBox .= '</select>';

        return $selectBox;
    }
}