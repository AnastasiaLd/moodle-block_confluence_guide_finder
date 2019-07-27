<?php

require_once( $CFG->libdir . '/externallib.php' );
require_once( '../../config.php' );

class block_confluence_guide_finder_external extends external_api {

  /* Get the user input */
  public static function find_guide_parameters() {
    return new external_function_parameters (
      array( 'keyword' => new external_value( PARAM_TEXT, 'the keyword' ) )
    );
  }

  /* Match the user input to confluence guides & return suggestion */
  public static function find_guide( $keyword ) {

    //validate parameters
    $params = self::validate_parameters( self::find_guide_parameters(),
    array( 'keyword' => $keyword ) );

    //build the endpoint
    $url = get_config( 'block_confluence_guide_finder', 'confluencedomain' ) . '/rest/api/content/search?cql=';
    $spacekey = get_config( 'block_confluence_guide_finder', 'confluencespacekey' );
    /* Find content that contains the user input (either an exact match or a "fuzzy" match) */
    $cqlquery = "(space in ( \"${spacekey}\" )) AND ( type = page ) AND ( text~ \"${keyword}\" )";
    $endpoint = $url .rawurlencode( $cqlquery );


    /* Connect to Confluence API */
    $ch = curl_init( $endpoint );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Accept' => 'application/json' ) );

    $response = curl_exec( $ch );

    if ( $response == true ) {
      /* JSON to array */
      $json = json_decode( $response , true );

      return $json;
    }

    else {
      echo  'Failed to load resource: '. curl_error( $ch ) ;
      print_error('connectionerror', 'block_confluence_guide_finder');
    }

    /* Close connection to Confluence API */
    curl_close( $ch );
  }

  /* Return pages suggestions */
  public static function find_guide_returns() {

    /* Define response array structure */
    return new external_single_structure(
        array(
            'results' => new external_multiple_structure(
                new external_single_structure(
                    array(

                        'title' => new external_value(PARAM_TEXT, 'page title', VALUE_OPTIONAL),

                        '_links' => new external_single_structure(

                          array(
                            'webui' => new external_value(PARAM_TEXT, 'webui', VALUE_OPTIONAL),
                        )

                        ),

                    )
                )
            ),

            '_links' => new external_single_structure(
              array(
                'base' => new external_value(PARAM_TEXT, 'base', VALUE_OPTIONAL),
              )
            )
        )
    );

  }

}
