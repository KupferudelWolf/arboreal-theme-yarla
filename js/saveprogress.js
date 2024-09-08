/* global wp, jQuery */

( function ( $ ) {
    /// Save the ID for the next page after 3 seconds.
    /// This will allow users to resume progress if they go to the index page.
    setTimeout( () => {
        const $next = $( '.nav-next' );
        if ( !$next.length ) return;
        const id = $next.attr( 'data-id' );
        $.cookie( 'next_page', id, { expires: 31, path: COOKIEPATH } );
        console.log( 'Progress saved.' );
    }, 3000 );
}( jQuery ) );