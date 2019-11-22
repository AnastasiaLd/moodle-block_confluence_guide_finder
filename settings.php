<?php

/**
 * Confluence guide finder block settings
 *
 * @package    block_confluence_guide_finder
 * @copyright  2019, London School of Economics
 */

defined('MOODLE_INTERNAL') || die;

$settings->add( new admin_setting_configtext( 'block_confluence_guide_finder/confluencedomain',
                                              get_string( 'confluencedomain', 'block_confluence_guide_finder' ),
                                              get_string( 'confluencedomain_desc', 'block_confluence_guide_finder' ), '', PARAM_URL ) );


$settings->add(new admin_setting_confightmleditor( 'block_confluence_guide_finder/blockfooter',
                                                    get_string( 'blockfooter', 'block_confluence_guide_finder' ),
                                                    get_string( 'blockfooter_desc', 'block_confluence_guide_finder' ), '' ) );
