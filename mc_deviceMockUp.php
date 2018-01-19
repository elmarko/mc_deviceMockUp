<?php
   /*
   Plugin Name: MC Device Mock Up
   Plugin URI: http://markcormack.co.uk
   Description: a plugin that simply wraps enclosed images in a device mockup use shortcode [mc_deviceMockUp caption="" specificStyles=""]***IMGS IN HERE***[/mc_deviceMockUp]
   Version: 0.1
   Author: Mark Cormack
   Author URI: http://markcormack.co.uk
   License: GPL2
   */
   
/******************* SHORTCODE DISPLAY STUFF *********************/
function mc_deviceMockUp_add_my_css_and_my_js_files(){
        wp_enqueue_style( 'mc_deviceMockUp styles', plugins_url('/css/mc_deviceMockUp-style.min.css', __FILE__), false, '1.0.0', 'all');
}

add_shortcode( 'mc_deviceMockUp', 'mc_deviceMockUp_to_frontend_shortcode_func' );

function returnEnclosingElement($caption){
	$enclosingElementType = "div";
	if (!empty($caption)){
		$enclosingElementType = "figure";
	}
	return $enclosingElementType;
}

function returnFigCaption($caption){
	$captionToReturn = "";
	if (!empty($caption)){
		$captionToReturn = '<figcaption class="col-sm-10 col-sm-offset-1 col-lg-8 col-lg-offset-2">'.$caption.'</figcaption>';
	}
	return $captionToReturn;
}

function returnSpecificStyles($specificStyles){
	$specificStylesToReturn = "";
	if (!empty($specificStyles)){
		$specificStylesToReturn = 'style="'.$specificStyles.'"';
	}
	return $specificStylesToReturn;
}

function returnModelsToUse($model = null, $models = null){
	$modelsToUse = [];
	if (isset($models)){
		$m = explode(",",$models);
		foreach ($m as $mx){
			$stringToUse = checkForVariancesIntThePassedModelString($mx);
			$modelsToUse[] = $stringToUse;
		}
	}else if (isset($model) && empty($models)){
		$stringToUse = checkForVariancesIntThePassedModelString($model);
		$modelsToUse[] = $stringToUse;
	}else{
		$modelsToUse[] = "iphone-6";
	}
	return $modelsToUse;
}

function checkForVariancesIntThePassedModelString($modelString){
	echo $modelString;
	switch($modelString){
		case (preg_match('/[iphone]?-?\d-?plus/i', $modelString) ? true : false):
			return "iphone-6-plus";
			break;
		case (preg_match('/[iphone]?-?\d/i', $modelString) ? true : false):
			return "iphone-6";
			break;
		case (preg_match('/[iphone]?-?se/i', $modelString) ? true : false):
			return "iphone-se";
			break;
		case (preg_match('/[iphone]?-?\5\D?/i', $modelString) ? true : false):
			return "iphone-se";
			break;
		case (preg_match('/[iphone]?-?4\D?/i', $modelString) ? true : false):
			return "iphone-4s";
			break;
		case (preg_match('/[apple]?-?watch/i', $modelString) ? true : false):
			return "watch";
			break;
		default: 
			return "iphone-6";
			break; 
	}
}

function returnNumberOfImagesAsAString($numberOfImages){
	echo $numberOfImages;

	switch ($numberOfImages) {
    case 1:
        return "one";
        break;
    case 2:
        return "two";
        break;
    case 3:
        return "three";
        break;
    case 4:
        return "four";
        break;
    default:
    	return "";
    break;
    }
}

function mc_deviceMockUp_to_frontend_shortcode_func($atts = [], $content = null){ 
	mc_deviceMockUp_add_my_css_and_my_js_files();
	$specificStyles = $atts["styles"];
	$caption = $atts["caption"];
	$model = $atts["model"];
	$models = $atts["models"];
	$specificStylesToUse = returnSpecificStyles($specificStyles);
	$enclosingElementToUse = returnEnclosingElement($caption);
	$figCaptionToUse = returnFigCaption($caption);
	$modelsToUse = returnModelsToUse($model, $models);
	var_dump($modelsToUse);
	ob_start();
		preg_match_all('/<img([^>]+)?>/', $content, $matches);
		$sortedArray = [];
		$ix = 0;
		$modelCount = count($modelsToUse);
		foreach ($matches[1] as $match){
			$sortedArray[] = '<div class="mc_mockup '.$modelsToUse[$ix].'"><img'.$match.'></div>';
			if (($ix + 1) < $modelCount){
				$ix += 1;
			}else{
				$ix = 0;
			}
		} 
		$stringToReturn = implode($sortedArray);
		$numberOfImages = count($matches[1]);
		$imageCountString = returnNumberOfImagesAsAString($numberOfImages);
		echo '<'.$enclosingElementToUse.' class="mc_mockup_cnt '.$imageCountString.'" '.$specificStylesToUse.' >';
		echo $stringToReturn;
		echo $figCaptionToUse;
		echo '</'.$enclosingElementToUse.'>';	
	return ob_get_clean();
 }
   
?>