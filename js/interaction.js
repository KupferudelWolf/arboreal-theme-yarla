/* global wp, jQuery */

( function ( $ ) {
    const params = new URLSearchParams( window.location.search );
    /// Keep the image enlarged.
    let is_enlarged = params.get( 'enlarged' );
    $( '.single .post-thumbnail' ).get( 0 ).classList.toggle( 'enlarged', is_enlarged );
    /// Keep the scroll position.
    document.documentElement.scrollTop = document.body.scrollTop = +params.get( 'scrollTop' );
    window.scrollTo( 0, +params.get( 'scrollTop' ) || 0 );
    window.history.replaceState( {}, document.title, window.location.pathname );

    /// Swipe to previous or next page.
    const m_pos = {
        x: null,
        active: null,
        prev: null,
        next: null,
        scroll_x: null
    };
    const url_prev = $( '.nav-previous a' ).attr( 'href' );
    const url_next = $( '.nav-next a' ).attr( 'href' );
    $( '.single .container:first-child' ).on( 'touchstart', ( event ) => {
        m_pos.active = true;
        m_pos.x = event.clientX || event.targetTouches[ 0 ].pageX;
        m_pos.scroll_x = document.documentElement.scrollTop || document.body.scrollTop;
    } ).on( 'touchmove', ( event ) => {
        if ( event.targetTouches.length !== 1 ) return;
        const x = event.clientX || event.targetTouches[ 0 ].pageX;
        const dx = ( x - m_pos.x ) * 2 / 3;
        const width = event.currentTarget.clientWidth / 3;
        event.currentTarget.style.left = `${ dx }px`;
        event.currentTarget.style.transition = 'none';
        m_pos.prev = dx >= width;
        m_pos.next = -dx >= width;
    } ).on( 'touchcancel touchend', ( event ) => {
        const tags = [];
        if ( is_enlarged ) tags.push( 'enlarged=true' );
        if ( m_pos.scroll_x ) tags.push( `scrollTop=${ m_pos.scroll_x }` );

        if ( url_prev && m_pos.prev ) {
            window.location.href = url_prev + ( tags.length ? `?${ tags.join( '&' ) }` : '' );
        } else if ( url_next && m_pos.next ) {
            window.location.href = url_next + ( tags.length ? `?${ tags.join( '&' ) }` : '' );
        }

        for ( const key of Object.keys( m_pos ) ) {
            m_pos[ key ] = null;
        }
        event.currentTarget.style.left = '';
        event.currentTarget.style.transition = '';
    } );

    /// Enlarge the image when clicked.
    $( '.single .post-thumbnail' ).on( 'click', ( event ) => {
        if ( m_pos.active ) return;
        event.currentTarget.classList.toggle( 'enlarged' );
        is_enlarged = event.currentTarget.classList.contains( 'enlarged' );
    } );

}( jQuery ) );
