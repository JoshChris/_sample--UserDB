<?php
function _clean($string)
{
    $clean_input = Array();
    if (is_array($string)) {
        foreach ($string as $k => $v) {
            $clean_input[$k] = _clean($v);
        }
    } else {
        $clean_input = trim(strip_tags($string));
    }
    return $clean_input;
}