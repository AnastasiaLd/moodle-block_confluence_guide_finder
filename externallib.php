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
    $url = get_config( 'block_confluence_guide_finder', 'confluencedomain' ) . 'rest/api/content/search?cql=';
    /* Find content that contains the user input (either an exact match or a "fuzzy" match) */
    $cqlquery = "type = page AND text ~ \"${keyword}\" ";
    $endpoint = $url .rawurlencode( $cqlquery );


    /* Connect to Confluence API */
    $ch = curl_init( $endpoint );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Accept' => 'application/json' ) );

    $response = curl_exec( $ch );
    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    $results = json_decode( $response , true );
    
    /* Depending on the HTTP response, return matching results or display error for failed API call */
    
    if ( $response === false ) {
    
      $error = array(

        'error' => array(

          'statusCode'=> curl_errno( $ch ),
          'message'=> curl_error( $ch ),
        )

      );

      return $error;
    
    }
    
    else {
      
      if ( $http_code !== 200 ) {
        
        $error_message = $results['message'];

        $error = array(

          'error' => array(

            'statusCode'=> $http_code,
            'message'=> $error_message,

          )
        );

        return $error;
      
      }
      
      else {
        
        return $results;
      
      }
    
    }

  }

  /* Return pages suggestions */
  public static function find_guide_returns() {

    /* Define response array structure */
    return new external_single_structure(
        array(
            /* HTTP status code and error message */
            'error' => new external_single_structure(
              array(
                'statusCode' => new external_value(PARAM_INT, 'http status code'),
                'message' => new external_value(PARAM_TEXT, 'error message'),
              )
            , 'error', VALUE_OPTIONAL),
          
            /* Matching results */
            'results' => new external_multiple_structure(
                new external_single_structure(
                    array(

                        'title' => new external_value(PARAM_TEXT, 'page title'),

                        '_links' => new external_single_structure(

                          array(
                            'webui' => new external_value(PARAM_TEXT, 'page url'),
                          )

                        ),

                    )
                )
            , 'results', VALUE_OPTIONAL),

            '_links' => new external_single_structure(
              array(
                'base' => new external_value(PARAM_TEXT, 'base', VALUE_OPTIONAL),
              )
            , 'link', VALUE_OPTIONAL)
        )
    );

  }

}
