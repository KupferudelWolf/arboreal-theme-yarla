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
    const $post_thumbnail_img = $( 'body.single .post-thumbnail img' );
    // const $transcript = $( '.entry-transcript.desktop-only table' );
    if ( $post_thumbnail.length ) {
        const $toggle_button = $( '.nav-toggle' );
        let timeout_active;
        /// Toggle area boxes.
        let boxes_mode = $toggle_button.attr( 'data-boxes_mode' ) || ( isMobile() ? 1 : 0 );
        --boxes_mode;
        const box_set_active = function () {
            switch ( boxes_mode ) {
                case 0: /// Hover
                    /// Show the boxes at start, then fade them away.
                    clearTimeout( timeout_active );
                    $post_thumbnail.addClass( 'boxes-active' );
                    timeout_active = setTimeout( () => {
                        $post_thumbnail.removeClass( 'boxes-active' );
                    }, 1000 );
                    break;
                case 1: /// Show
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
                case 2: /// Hide
                    if ( typeof ( timeout_active ) === 'undefined' ) {
                        /// Have the transcript visible at the start.
                        /// Only do this if the page loads with this mode on.
                        // $transcript.css( 'transition', 'none' );
                        // timeout_active = setTimeout( () => {
                        //    $transcript.css( 'transition', '' );
                        // }, 10 );
                    }
                    break;
            }
        };
        /// Set the text in the button when resizing.
        const renameBox = function () {
            let str = '';
            switch ( +boxes_mode ) {
                case 0: /// Hover
                    str = isMobile() ? 'Tap' : 'Hover';
                    break;
                case 1: /// Show
                    str = 'Show';
                    break;
                case 2: /// Hide
                    str = 'Hide';
                    break;
            }
            $toggle_button.children( 'a' ).html( 'Translations: ' + str );
        };

        /// Scale the image container appropriately.
        const scaleImageContainer = function () {
            const img_w = $post_thumbnail_img.attr( 'width' );
            const img_h = $post_thumbnail_img.attr( 'height' );
            const w = $post_thumbnail.parent().width();
            const zoom = Math.max( Math.floor( w / img_w ), 1 );
            $post_thumbnail.css( {
                'aspect-ratio': img_w / img_h,
                'max-width': `${ img_w * zoom }px`,
                'height': `${ img_h * zoom }px`
            } );
        };
        scaleImageContainer();

        const onResize = function () {
            scaleImageContainer();
            renameBox();
        };
        $( window ).on( 'resize', onResize );
        onResize();
        const box_toggle_on_click = function () {
            boxes_mode = ( boxes_mode + 1 ) % 3;
            // if ( isMobile() && boxes_mode === 0 ) {
            //     boxes_mode = 1;
            // }
            $post_thumbnail.removeClass( 'boxes-hover boxes-show boxes-hide' );
            // $transcript.removeClass( 'active' );
            clearTimeout( timeout_tutorial );
            $( '.areamap-tutorial' ).remove();
            switch ( boxes_mode ) {
                case 0: /// Hover
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
                    $post_thumbnail.addClass( 'boxes-show' );
                    break;
                case 2: /// Hide
                    $post_thumbnail.addClass( 'boxes-hide' );
                    // $transcript.addClass( 'active' );
                    break;
            }
            $toggle_button.attr( 'data-boxes_mode', boxes_mode );
            $.cookie( 'boxes_mode', boxes_mode, { expires: 365, path: COOKIEPATH } );
            box_set_active();
            renameBox();
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
            const left = offset.left;
            const top = offset.top;
            const width = $target.width();
            const height = $target.height();
            // const width = window.innerWidth;
            // const height = window.innerHeight;
            // const left = window.scrollX;
            // const top = window.scrollY;
            const x = Math.min( Math.max( mouse_x - left, 0 ), width );
            const y = Math.min( Math.max( mouse_y - top, 0 ), height );
            return [
                Math.round( 10000 * x / width ) / 100,
                Math.round( 10000 * y / height ) / 100
            ];
        };
        const toSXY = function ( event ) {
            const mouse_x = event.pageX;
            const mouse_y = event.pageY;
            if ( !mouse_x || !mouse_y ) return null;
            const width = window.innerWidth;
            const height = window.innerHeight;
            const left = window.scrollX;
            const top = window.scrollY;
            const x = Math.min( Math.max( mouse_x - left, 0 ), width );
            const y = Math.min( Math.max( mouse_y - top, 0 ), height );
            return [
                Math.round( 10000 * x / width ) / 100,
                Math.round( 10000 * y / height ) / 100
            ];
        };

        /// Hoverbox functionality.
        $( '.hoverbox' ).parent().on( 'hover mouseover mousemove', function ( event ) {
            const $this = $( this );
            const [ left_val, top_val ] = toXY( event, $this );
            let [ left, top ] = toSXY( event );
            if ( left === null || top === null ) return;
            const $boxes = $this.children( '.hoverbox' );
            if ( _admin_data.dragging ) return;
            // const style = {
            //     width: '',
            //     height: '',
            //     opacity: ''
            // };
            // if ( isMobile() ) {
            //     const width = document.body.clientWidth;
            //     $boxes.css( style );
            //     const off_left = $this.offset().left;
            //     const off_right = off_left + $this.width();
            //     if ( off_left < 0 ) {
            //         left = `calc(${ left }% + ${ -Math.ceil( off_left ) }px)`;
            //     } else if ( off_right > width ) {
            //         left = `calc(${ left }% - ${ Math.ceil( off_right ) - width }px)`;
            //     }
            // } else {
            //     // if ( left > 50 ) {
            //     //     left -= 100 * $this.width() / window.innerWidth;
            //     // }
            //     // if ( top > 50 ) {
            //     //     top -= 100 * $this.height() / window.innerHeight;
            //     // }
            // }
            // style.left = left + '%';
            // style.top = top + '%';
            const screen_w = document.documentElement.clientWidth;
            const screen_h = document.documentElement.clientHeight;
            const box_w = $boxes.outerWidth();
            const box_h = $boxes.outerHeight();
            const scroll_left = document.body.scrollLeft;
            const scroll_top = document.body.scrollTop;
            let min_left = scroll_left;
            let max_left = scroll_left + screen_w - box_w;
            let min_top = scroll_top;
            let max_top = scroll_top + screen_h - box_h;
            if ( isMobile() ) {
                min_left += box_w / 2;
                max_left += box_w / 2;
                min_top += box_h / 2;
                max_top += box_h / 2;
            }
            const style = {
                width: '',
                height: '',
                left: `min(max(${ left }%, ${ min_left }px), ${ max_left }px)`,
                top: `min(max(${ top }%, ${ min_top }px), ${ max_top }px)`,
                opacity: ''
            };
            $boxes.css( style );
            _admin_data.$box.html(
                `left: ${ left_val + '%' };</br>top: ${ top_val + '%' };`
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
                    if ( isMobile() ) return;
                    const $this = $( this );
                    const [ left_val, top_val ] = toXY( event, $this );
                    const [ left, top ] = toSXY( event );
                    if ( left === null || top === null ) return;
                    _admin_data.dragging_start = true;
                    _admin_data.x1 = left_val;
                    _admin_data.y1 = top_val;
                    _admin_data.sx1 = left;
                    _admin_data.sy1 = top;
                } )
                .on( 'mousemove', function ( event ) {
                    if ( isMobile() ) return;
                    if ( _admin_data.dragging ) {
                        const [ left, top ] = toSXY( event );
                        _admin_data.sx2 = left;
                        _admin_data.sy2 = top;
                        _admin_data.$box.html( '' ).css( {
                            left: Math.min( _admin_data.sx1, _admin_data.sx2 ) + '%',
                            top: Math.min( _admin_data.sy1, _admin_data.sy2 ) + '%',
                            right: ( 100 - Math.max( _admin_data.sx1, _admin_data.sx2 ) ) + '%',
                            bottom: ( 100 - Math.max( _admin_data.sy1, _admin_data.sy2 ) ) + '%',
                            width: 'auto',
                            height: 'auto',
                            opacity: '50%'
                        } );
                    } else if ( _admin_data.dragging_start ) {
                        const [ left, top ] = toSXY( event );
                        if ( left === null || top === null ) return;
                        if (
                            Math.abs( _admin_data.sx1 - left ) > 2 ||
                            Math.abs( _admin_data.sy1 - top ) > 2
                        ) {
                            _admin_data.dragging = true;
                            _admin_data.$box.addClass( 'active' );
                        }
                    }
                } )
                .on( 'mouseup mouseleave', function ( event ) {
                    if ( isMobile() ) return;
                    if ( !_admin_data.dragging ) return;
                    _admin_data.dragging_start = false;
                    _admin_data.dragging = false;
                    _admin_data.$box.removeClass( 'active' ).css( 'inset', '' );
                    const $this = $( this );
                    const [ left_val, top_val ] = toXY( event, $this );
                    if ( left_val === null || top_val === null ) return;
                    const data = {
                        x1: _admin_data.x1,
                        y1: _admin_data.y1,
                        x2: left_val,
                        y2: top_val
                    };
                    navigator.clipboard.writeText( JSON.stringify( data ) );
                    console.log( 'Box:', data, '\n(copied to clipboard)' );
                } );
        } else {
            $post_thumbnail
                .on( 'mousedown', function ( event ) {
                    event.preventDefault();
                } );
        }
    }

}( jQuery ) );
