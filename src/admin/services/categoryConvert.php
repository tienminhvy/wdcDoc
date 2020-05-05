<?php 
	if (!defined('isSet')) {
		die('Direct access is not allowed');
	}
    function convert_category($str) {
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);
		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'a', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'e', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'i', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'o', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'u', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'y', $str);
		$str = preg_replace("/(Đ)/", 'd', $str);
		$str = preg_replace("/(A)/", 'a', $str);
		$str = preg_replace("/(B)/", 'b', $str);
		$str = preg_replace("/(C)/", 'c', $str);
		$str = preg_replace("/(D)/", 'd', $str);
		$str = preg_replace("/(E)/", 'e', $str);
		$str = preg_replace("/(F)/", 'f', $str);
		$str = preg_replace("/(G)/", 'g', $str);
		$str = preg_replace("/(H)/", 'h', $str);
		$str = preg_replace("/(I)/", 'i', $str);
		$str = preg_replace("/(I)/", 'j', $str);
		$str = preg_replace("/(K)/", 'k', $str);
		$str = preg_replace("/(L)/", 'l', $str);
		$str = preg_replace("/(M)/", 'm', $str);
		$str = preg_replace("/(N)/", 'n', $str);
		$str = preg_replace("/(O)/", 'o', $str);
		$str = preg_replace("/(P)/", 'p', $str);
		$str = preg_replace("/(Q)/", 'q', $str);
		$str = preg_replace("/(R)/", 'r', $str);
		$str = preg_replace("/(S)/", 's', $str);
		$str = preg_replace("/(T)/", 't', $str);
		$str = preg_replace("/(U)/", 'u', $str);
		$str = preg_replace("/(V)/", 'v', $str);
		$str = preg_replace("/(W)/", 'w', $str);
		$str = preg_replace("/(X)/", 'x', $str);
		$str = preg_replace("/(Y)/", 'y', $str);
		$str = preg_replace("/(Z)/", 'z', $str);
		$str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '-', $str);
        $str = preg_replace("/( )/", '-', $str);
        $str = preg_replace("/---/", '-', $str);
        $str = preg_replace("/\"/", '', $str);
        // $str = preg_replace("/-/", '', $str);
		return $str;
	}
?>