/* global wp, jQuery */

( function ( $ ) {
    // const params = new URLSearchParams( window.location.search );
    // /// Keep the image enlarged.
    // let is_enlarged = params.get( 'enlarged' );
    // $( '.single .post-thumbnail' ).get( 0 ).classList.toggle( 'enlarged', is_enlarged );
    // /// Keep the scroll position.
    // document.documentElement.scrollTop = document.body.scrollTop = +params.get( 'scrollTop' );
    // window.scrollTo( 0, +params.get( 'scrollTop' ) || 0 );
    // window.history.replaceState( {}, document.title, window.location.pathname );

    const _admin_data = {};

    function isMobile() {
        return '' + getComputedStyle( document.body ).getPropertyValue( '--is-mobile' ) === 'true';
    }

    window.addEventListener( 'touchstart', function ( event ) {
        /// Prevent mobile swipe actions.
        const touch_margin = 0.05;
        for ( let i = 0, l = event.touches.length; i < l; ++i ) {
            const touch = event.touches[ i ];
            if ( touch.clientX < window.innerWidth * touch_margin || touch.clientX > window.innerWidth * ( 1 - touch_margin ) ) {
                event.preventDefault();
                return;
            }
        }
    }, { passive: false } );

    /// Swipe to previous or next page.
    /*
    const m_pos = {
        x: null,
        active: null,
        prev: null,
        next: null,
        scroll_x: null
    };
    const swipe_limit = 0.15;
    const swipe_speed = 2 / 3;
    const url_prev = $( '.nav-previous a' ).attr( 'href' );
    const url_next = $( '.nav-next a' ).attr( 'href' );
    $( '.single .container' ).first().on( 'touchstart', ( event ) => {
        m_pos.active = true;
        m_pos.x = event.clientX || event.targetTouches[ 0 ].pageX;
        m_pos.scroll_x = document.documentElement.scrollTop || document.body.scrollTop;
        $( '.swipe-nav-left, .swipe-nav-right' ).addClass( 'show' );
    } ).on( 'touchmove', ( event ) => {
        /// Ignore multi-touch.
        if ( event.targetTouches.length !== 1 ) return;
        /// Ignore if scrolled down.
        const rect = event.currentTarget.getBoundingClientRect();
        if ( -rect.top > rect.height / 6 ) {
            event.currentTarget.style.left = '';
            event.currentTarget.style.transition = '';
            return;
        }

        const x = event.clientX || event.targetTouches[ 0 ].pageX;
        const dx = ( x - m_pos.x ) * swipe_speed;
        const width = event.currentTarget.clientWidth * swipe_limit;
        event.currentTarget.style.left = `${ dx }px`;
        event.currentTarget.style.transition = 'none';
        m_pos.prev = dx >= width;
        m_pos.next = -dx >= width;
        $( '.swipe-nav-left' ).toggleClass( 'active', m_pos.prev );
        $( '.swipe-nav-right' ).toggleClass( 'active', m_pos.next );
    } ).on( 'touchcancel touchend', ( event ) => {
        const tags = [];
        // if ( is_enlarged ) tags.push( 'enlarged=true' );
        // if ( m_pos.scroll_x ) tags.push( `scrollTop=${ m_pos.scroll_x }` );

        if ( url_prev && m_pos.prev ) {
            window.location = url_prev + ( tags.length ? `?${ tags.join( '&' ) }` : '' );
        } else if ( url_next && m_pos.next ) {
            window.location = url_next + ( tags.length ? `?${ tags.join( '&' ) }` : '' );
        }

        for ( const key of Object.keys( m_pos ) ) {
            m_pos[ key ] = null;
        }
        event.currentTarget.style.left = '';
        event.currentTarget.style.transition = '';
        $( '.swipe-nav-left, .swipe-nav-right' ).removeClass( 'show' );
    } );
    */

    /// Enlarge the image when clicked.
    $( 'body.single .post-thumbnail' ).on( 'pointerup', ( event ) => {
        if ( event.pointerType === 'touch' ) return;
        if ( _admin_data.dragging ) return;
        event.currentTarget.classList.toggle( 'enlarged' );
        is_enlarged = event.currentTarget.classList.contains( 'enlarged' );
        _admin_data.dragging_start = _admin_data.dragging = false;
    } );

    const post_thumbnail = $( '.post .post-thumbnail' );
    if ( post_thumbnail.length ) {
        /// Toggle area boxes.
        const toggle_button = $( '.nav-toggle' );
        let boxes_mode = toggle_button.attr( 'data-boxes_mode' ) || ( isMobile() ? 1 : 0 );
        --boxes_mode;
        const box_toggle_on_click = function () {
            boxes_mode = ( boxes_mode + 1 ) % 3;
            if ( isMobile() && boxes_mode === 0 ) {
                boxes_mode = 1;
            }
            post_thumbnail.removeClass( 'boxes-no_hover boxes-sticky' );
            switch ( boxes_mode ) {
                case 0: /// Hover
                    toggle_button.children( 'a' ).html( 'Translations: Hover' );
                    break;
                case 1: /// Show
                    toggle_button.children( 'a' ).html( 'Translations: Show' );
                    post_thumbnail.addClass( 'boxes-no_hover boxes-sticky' );
                    break;
                case 2: /// Hide
                    toggle_button.children( 'a' ).html( 'Translations: Hide' );
                    post_thumbnail.addClass( 'boxes-no_hover' );
                    break;
            }
            toggle_button.attr( 'data-boxes_mode', boxes_mode );
            $.cookie( 'boxes_mode', boxes_mode, { expires: 31, path: COOKIEPATH } );
        };
        toggle_button.on( 'click', box_toggle_on_click );
        box_toggle_on_click();

        const toXY = function ( event, $target ) {
            const mouse_x = event.pageX;
            const mouse_y = event.pageY;
            if ( !mouse_x || !mouse_y ) return null;
            const offset = $target.offset();
            const width = $target.width();
            const height = $target.height();
            const x = mouse_x - offset.left;
            const y = mouse_y - offset.top;
            const left = Math.round( 10000 * x / width ) / 100;
            const top = Math.round( 10000 * y / height ) / 100;
            return [ left, top ];
        };

        /// Hoverbox functionality.
        $( '.hoverbox' ).parent().on( 'hover mouseover mousemove', function ( event ) {
            const $this = $( this );
            const [ left, top ] = toXY( event, $this );
            if ( left === null || top === null ) return;
            const $boxes = $this.children( '.hoverbox' );
            if ( _admin_data.dragging ) return;
            $boxes.css( {
                left: left + '%',
                top: top + '%',
                right: '',
                bottom: '',
                width: '',
                height: '',
                opacity: ''
            } );
            $boxes.filter( '.adminbox' ).html(
                `left: ${ left + '%' };</br>top: ${ top + '%' };`
            );
        } );

        /// Interactive translation tool.
        $( 'body.admin-bar.single .post-thumbnail' )
            .on( 'mousedown', function ( event ) {
                event.preventDefault();
                const $this = $( this );
                const [ left, top ] = toXY( event, $this );
                if ( left === null || top === null ) return;
                _admin_data.dragging_start = true;
                _admin_data.x1 = left;
                _admin_data.y1 = top;
            } )
            .on( 'mousemove', function ( event ) {
                if ( _admin_data.dragging ) {
                    const $this = $( this );
                    const [ left, top ] = toXY( event, $this );
                    _admin_data.x2 = left;
                    _admin_data.y2 = top;
                    $( '.adminbox' ).html( '' ).css( {
                        left: Math.min( _admin_data.x1, _admin_data.x2 ) + '%',
                        top: Math.min( _admin_data.y1, _admin_data.y2 ) + '%',
                        right: ( 100 - Math.max( _admin_data.x1, _admin_data.x2 ) ) + '%',
                        bottom: ( 100 - Math.max( _admin_data.y1, _admin_data.y2 ) ) + '%',
                        width: 'auto',
                        height: 'auto',
                        opacity: '50%'
                    } );
                } else if ( _admin_data.dragging_start ) {
                    const $this = $( this );
                    const [ left, top ] = toXY( event, $this );
                    if ( left === null || top === null ) return;
                    if (
                        Math.abs( _admin_data.x1 - left ) > 2 ||
                        Math.abs( _admin_data.y1 - top ) > 2
                    ) {
                        _admin_data.dragging = true;
                    }
                }
            } )
            .on( 'mouseup mouseleave', function ( event ) {
                if ( !_admin_data.dragging ) return;
                const $this = $( this );
                const [ left, top ] = toXY( event, $this );
                if ( left === null || top === null ) return;
                const data = {
                    x1: Math.min( _admin_data.x1, _admin_data.x2 ),
                    y1: Math.min( _admin_data.y1, _admin_data.y2 ),
                    x2: Math.max( _admin_data.x1, _admin_data.x2 ),
                    y2: Math.max( _admin_data.y1, _admin_data.y2 )
                };
                navigator.clipboard.writeText( JSON.stringify( data, null, 4 ) );
                console.log( 'Box:', data, '\n(copied to clipboard)' );
                _admin_data.dragging_start = false;
                _admin_data.dragging = false;
            } );
    }

}( jQuery ) );
