define( [ 'jquery', 'core/templates', 'core/notification', 'core/ajax' ], function( $, templates, notification, ajax ) {

  function delaySearch( callback, ms ) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout( timer );
      timer = setTimeout( function() {
        callback.apply( context, args );
      }, ms || 0 );
    };
  }

  function searchGuide( keyword ) {

    return ajax.call([{
      methodname: 'block_confluence_guide_finder_find_guide',
      args: { 'keyword': keyword }
    }])[0].fail(function( exception ) {

      notification.exception( exception );;

    }).then( function( response ) {
      console.log('------------response------------');
      console.log( response );

     /* Show found guides */
     templates.render( 'block_confluence_guide_finder/result', response )
              .then( function( html,js ){
                templates.replaceNodeContents( '.block_confluence_guide_finder .guide-list', html, js );
              } )

    } );

  }

  return {
    init: function() {
      $( '#search' ).on( 'keypress, keyup, keydown', delaySearch( function( e ) {

        var keyword = $( this ).val();

        /* Check empty search input */
        if ( keyword != "" ) {
          $('.guide-list').show();
          searchGuide( keyword );
        }

        else {
          $('.guide-list').hide()
        }


      }, 1000 ) );

    }
  };

});
