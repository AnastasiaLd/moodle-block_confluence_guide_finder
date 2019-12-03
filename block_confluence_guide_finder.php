<?php

class block_confluence_guide_finder extends block_base {

  public function init() {
    $this->title = get_string( 'confluenceguidefinder', 'block_confluence_guide_finder' );
  }

  public function has_config() {
    return True;
  }

  public function get_content() {
    global $OUTPUT;
    global $COURSE;
    require_once(dirname(__FILE__) . '/../../config.php');


      if ( $this->content !== null ) {
        return $this->content;
      }

      /* Only course editors can view this block */
      $context = context_course::instance( $COURSE->id );

      if ( !has_capability( 'block/confluence_guide_finder:view', $context ) ) {
          return '';
      }

      if ( empty( $this->instance ) ) {
        $this->content = '';

        return $this->content;
      }

      /* Render block content */
      $this->content = new stdClass();
      $this->content->text = $OUTPUT->render_from_template( 'block_confluence_guide_finder/searchbar', null );
      $this->content->footer = get_config( 'block_confluence_guide_finder', 'blockfooter' );;

      return $this->content;
  }

  /* Get javascript for search feature */
   public function get_required_javascript() {
     parent::get_required_javascript();

     $this->page->requires->js_call_amd('block_confluence_guide_finder/searcher', 'init');
   }
}
