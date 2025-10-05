/// JSONify content and title columns in Meta Box.

( function ( $ ) {
    const JSONifyRows = function () {
        $( '.mb-translation-data .rwmb-clone:not(.rwmb-clone-template)' ).each( ( i, elem ) => {
            const $elem = $( elem );
            for ( let ind = 0; ind < 2; ++ind ) {
                const targ = $elem.find( 'label:nth-of-type(' + ( ind + 1 ) + ') input' );
                let val = targ.val();
                try {
                    JSON.parse( val );
                } catch ( e ) {
                    val = val.replace( /\\/g, '&bsol;' ).replace( /\"/g, '\\\"' );
                    val = '{"en": "' + val + '"}';
                    targ.val( val );
                }
            }
        } );
    };

    $( '#jsonify_rows' ).on( 'click', JSONifyRows );
}( jQuery ) );
