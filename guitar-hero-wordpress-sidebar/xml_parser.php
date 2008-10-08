<?php

  static $plugin_path = "wp-content/plugins/guitar-hero/";
  
  // http://community.guitarhero.com/accounts/feed/gh3/755673.xml
  // http://community.guitarhero.com/slots/performances_feed/gh3/5095145.xml  
  # $default_file = $plugin_path. "unknown_profile.xml";
  $file = "http://community.guitarhero.com/accounts/feed/gh3/". get_option('ghID') .".xml";
  
  # $file = $default_file;
  
  $depth = array();
  
  $data = array();
  
  $element = "";
  static $username = "unknown";
  static $groupies = 0;
  static $rocker_status = "unknown";
  static $campaign_overall_rank = 0;
  static $cash = 0;
  
  function startElement($parser, $name, $attrs) 
  {
    global $element;
    $element = strtolower($name);
  }
  
  function endElement($parser, $name) 
  {
  }
  
  function characterDataHandler ($parser, $data) {
    global $username, $element, $groupies, $rocker_status, $campaign_overall_rank, $cash;
    
    switch ($element) {
    
    case "username":  $username = $data; 
                      break;
    case "groupies":  $groupies = $data;
                      break;
    case "rocker_status":  $rocker_status = $data;
                      break;
    case "campaign_overall_rank": $campaign_overall_rank = $data;
                      break;
    case "cash":      $cash = $data;
                      break;
    }    
    $element = "";
  }
  
  $xml_parser = xml_parser_create();
  xml_set_element_handler($xml_parser, "startElement", "endElement");
  xml_set_character_data_handler( $xml_parser, "characterDataHandler");
  
  // caching!
  $cachefile = $plugin_path."cachefile.xml";
  
  if ((time() - @fileatime($cachefile)) < 3600) {
    $use_cache_file = true;
    $file = $cachefile;
  } else {
    $use_cache_file = false;
  }
  
  if ($fp = @fopen($file, "r")) {
  
    if (!$use_cache_file) {
      $cachefile = @fopen($cachefile,"w");
    }
    
    while ($data = fread($fp, 4096)) {
      if (!$use_cache_file) {
        fwrite($cachefile, $data);
      }  
    
      if (!xml_parse($xml_parser, $data, feof($fp))) {
          die(sprintf("XML error: %s at line %d",
          xml_error_string(xml_get_error_code($xml_parser)),
          xml_get_current_line_number($xml_parser)));
      }
    }
    xml_parser_free($xml_parser);
  }
  
  function gh_getUsername() {
    global $username;
    return $username;
  }
  
  function gh_getGroupies() {
    global $groupies;
    return $groupies;
  }
  
  function gh_getID() {
    return get_option('ghID');
  }
  
  function gh_getRockerStatus() {
    global $rocker_status;
    return $rocker_status;
  }
  
  function gh_getRank() {
    global $campaign_overall_rank;
    return $campaign_overall_rank;
  }
  
  function gh_getCash() {
    global $cash;
    return $cash;
  }
?>
