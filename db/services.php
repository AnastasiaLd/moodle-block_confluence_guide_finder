<?php

//define the functions
$functions = array(
  'block_confluence_guide_finder_find_guide' => array(
    'classname' => 'block_confluence_guide_finder_external',
    'methodname' => 'find_guide',
    'classpath' => '',
    'description' => 'Find confluence guides with content that contains the user input (either an exact match or a "fuzzy" match)',
    'ajax' => true,
  )
);

$services = array(
  'confluence_guide_finder_service' => array(
    'functions' => array('block_confluence_guide_finder_find_guide'),
    'requiredcapability' => '',
    'restrictedusers' =>0,
    'enabled'=>1,
  )
);
