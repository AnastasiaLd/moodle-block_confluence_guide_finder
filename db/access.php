<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
  'block/confluence_guide_finder:view' => array(
      'captype' => 'read',
      'contextlevel' => CONTEXT_COURSE,
      'archetypes' => array(
        'teacher' => CAP_ALLOW,
        'editingteacher' => CAP_ALLOW,
        'manager' => CAP_ALLOW
      ),

    ),

      'block/confluence_guide_finder:myaddinstance' => array(
          'captype' => 'write',
          'contextlevel' => CONTEXT_SYSTEM,
          'archetypes' => array(
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
          ),

          'clonepermissionsfrom' => 'moodle/my:manageblocks'
      ),

      'block/confluence_guide_finder:addinstance' => array(

          'captype' => 'write',
          'contextlevel' => CONTEXT_BLOCK,
          'archetypes' => array(
              'teacher' => CAP_ALLOW,
              'editingteacher' => CAP_ALLOW,
              'manager' => CAP_ALLOW
          ),

          'clonepermissionsfrom' => 'moodle/site:manageblocks'
      ),
);
