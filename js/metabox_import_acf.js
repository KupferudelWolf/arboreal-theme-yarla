/// Imports ACF data into Meta Box.

( function ( $ ) {
    $( '#import_acf' ).on( 'click', function () {
        const field = acf.getFields( {
            name: 'hoverbox_table'
        } )[ 0 ];

        if ( !field ) {
            console.warn( 'The ACF field was not found.' );
            return;
        }

        let val = field.val();
        val = decodeURIComponent( val );
        val = val
            .replace( /\+/g, ' ' )
            .replace( /\\n/g, ' ' )
            .trim();
        if ( !val ) {
            console.warn( 'The ACF field is empty.' );
            return;
        }
        const data_full = JSON.parse( val );
        if ( !data_full.b || data_full.b.length === 0 ) {
            console.error( 'No ACF data was found.' );
            return;
        }
        const data = data_full.b;
        /// Get the current rows.
        const rows = $( '.mb-translation-data .rwmb-clone:not(.rwmb-clone-template)' ).toArray();

        const $btn_add = $( '.mb-translation-data .rwmb-button.button.add-clone' );
        data.forEach( ( row ) => {
            /// Add a new row.
            $btn_add.click();
            /// Get the new row.
            const $row = $( '.mb-translation-data .rwmb-clone:not(.rwmb-clone-template)' ).last();
            /// Fill out each label inside the new row.
            row.forEach( ( cell, ind ) => {
                let val = cell.c;
                $row.find( 'label:nth-of-type(' + ( ind + 1 ) + ') input' ).val( val );
            } );
        } );

        /// Delete the old rows.
        rows.forEach( ( row ) => {
            $( row ).find( '.rwmb-button.remove-clone' ).click();
        } );
    } );
}( jQuery ) );
