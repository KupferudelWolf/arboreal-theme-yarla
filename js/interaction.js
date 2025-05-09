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
    _admin_data.$box = $( '.adminbox' );
    let timeout_tutorial;

    function isMobile() {
        return getComputedStyle( document.body ).getPropertyValue( '--is-mobile' ) === 'true';
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

    /// Enlarge the image when clicked.
    $( 'body.single .post-thumbnail' ).on( 'pointerup', ( event ) => {
        if ( isMobile() ) return;
        // if ( event.pointerType === 'touch' ) return;
        if ( _admin_data.dragging ) return;
        if ( event.button !== 0 ) return;
        event.currentTarget.parentElement.classList.toggle( 'enlarged' );
        is_enlarged = event.currentTarget.parentElement.classList.contains( 'enlarged' );
        _admin_data.dragging_start = _admin_data.dragging = false;
    } );

    const $post_thumbnail = $( 'body.single .post-thumbnail' );
    if ( $post_thumbnail.length ) {
        const $toggle_button = $( '.nav-toggle' );
        let timeout_active;
        /// Toggle area boxes.
        let boxes_mode = $toggle_button.attr( 'data-boxes_mode' ) || ( isMobile() ? 1 : 0 );
        --boxes_mode;
        const box_set_active = function () {
            switch ( boxes_mode ) {
                case 0:
                    /// Show the boxes at start, then fade them away.
                    clearTimeout( timeout_active );
                    $post_thumbnail.addClass( 'boxes-active' );
                    timeout_active = setTimeout( () => {
                        $post_thumbnail.removeClass( 'boxes-active' );
                    }, 1000 );
                    break;
                case 1:
                    if ( typeof ( timeout_active ) === 'undefined' ) {
                        /// Show the comic without boxes, then fade them in.
                        /// Only do this if the page loads with this mode on.
                        $post_thumbnail.removeClass( 'boxes-active' );
                        timeout_active = setTimeout( () => {
                            $post_thumbnail.addClass( 'boxes-active' );
                        }, 500 );
                    } else {
                        /// Force the boxes to show.
                        /// This happens if the mode is switched after loading.
                        clearTimeout( timeout_active );
                        $post_thumbnail.addClass( 'boxes-active' );
                    }
                    break;
            }
        };
        const box_toggle_on_click = function () {
            boxes_mode = ( boxes_mode + 1 ) % 3;
            // if ( isMobile() && boxes_mode === 0 ) {
            //     boxes_mode = 1;
            // }
            $post_thumbnail.removeClass( 'boxes-hover boxes-show boxes-hide' );
            clearTimeout( timeout_tutorial );
            $( '.areamap-tutorial' ).remove();
            switch ( boxes_mode ) {
                case 0: /// Hover
                    $toggle_button.children( 'a' ).html( isMobile() ? 'Translations: Tap' : 'Translations: Hover' );
                    $post_thumbnail.addClass( 'boxes-hover' );
                    /// Tutorial.
                    if ( $.cookie( 'has_hovered' ) != '1' ) {
                        timeout_tutorial = setTimeout( () => {
                            if ( $.cookie( 'has_hovered' ) != '1' ) {
                                const $elem = $( '<div>' );
                                $elem.addClass( 'areamap areamap-tutorial' );
                                $elem.html( 'Hover over the Yarla text to see the English translation.' );
                                $elem.css( 'opacity', '0' );
                                $post_thumbnail.append( $elem );
                                setTimeout( () => {
                                    $elem.css( 'opacity', '1' );
                                }, 100 );
                            }
                        }, 5000 );
                    }
                    break;
                case 1: /// Show
                    $toggle_button.children( 'a' ).html( 'Translations: Show' );
                    $post_thumbnail.addClass( 'boxes-show' );
                    // $post_thumbnail.addClass( 'boxes-no_hover boxes-sticky' );
                    break;
                case 2: /// Hide
                    $toggle_button.children( 'a' ).html( 'Translations: Hide' );
                    $post_thumbnail.addClass( 'boxes-hide' );
                    // $post_thumbnail.addClass( 'boxes-no_hover' );
                    break;
            }
            $toggle_button.attr( 'data-boxes_mode', boxes_mode );
            $.cookie( 'boxes_mode', boxes_mode, { expires: 365, path: COOKIEPATH } );
            box_set_active();
        };
        $toggle_button.on( 'click', box_toggle_on_click );
        box_toggle_on_click();

        /// Set boxes active on scroll.
        document.addEventListener( 'scroll', box_set_active );

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
            let [ left, top ] = toXY( event, $this );
            if ( left === null || top === null ) return;
            const $boxes = $this.children( '.hoverbox' );
            if ( _admin_data.dragging ) return;
            const style = {
                left: left + '%',
                top: top + '%',
                right: '',
                bottom: '',
                width: '',
                height: '',
                opacity: ''
            };
            if ( isMobile() ) {
                const width = document.body.clientWidth;
                $boxes.css( style );
                const off_left = $boxes.offset().left;
                const off_right = off_left + $boxes.width();
                if ( off_left < 0 ) {
                    style.left = `calc(${ left }% + ${ -Math.ceil( off_left ) }px)`;
                } else if ( off_right > width ) {
                    style.left = `calc(${ left }% - ${ Math.ceil( off_right ) - width }px)`;
                }
            }
            $boxes.css( style );
            _admin_data.$box.html(
                `left: ${ left + '%' };</br>top: ${ top + '%' };`
            );
        } );

        /// Tutorial.
        $( '.areamap' ).on( 'hover mouseover mousemove', function ( event ) {
            if ( boxes_mode !== 0 ) return;
            $( '.areamap-tutorial' ).remove();
            $.cookie( 'has_hovered', 1, { expires: 365, path: COOKIEPATH } );
        } );

        /// Interactive translation tool.
        if ( _admin_data.$box.length ) {
            $post_thumbnail
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
                        x1: _admin_data.x1,
                        y1: _admin_data.y1,
                        x2: _admin_data.x2,
                        y2: _admin_data.y2
                    };
                    navigator.clipboard.writeText( JSON.stringify( data, null, 4 ) );
                    console.log( 'Box:', data, '\n(copied to clipboard)' );
                    _admin_data.dragging_start = false;
                    _admin_data.dragging = false;
                } );
        } else {
            $post_thumbnail
                .on( 'mousedown', function ( event ) {
                    event.preventDefault();
                } );
        }
    }

}( jQuery ) );
