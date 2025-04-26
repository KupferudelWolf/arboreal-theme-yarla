/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function () {
	function isMobile() {
		return getComputedStyle( document.body ).getPropertyValue( '--is-mobile' ) === 'true';
	}

	const siteNavigation = document.getElementById( 'site-navigation' );

	// Return early if the navigation doesn't exist.
	if ( !siteNavigation ) {
		return;
	}

	const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];

	// Return early if the button doesn't exist.
	if ( 'undefined' === typeof button ) {
		return;
	}

	const menus = siteNavigation.getElementsByTagName( 'li' );

	// Hide menu toggle button if menu is empty and return early.
	if ( !menus.length ) {
		button.style.display = 'none';
		return;
	}

	// if ( !menus.classList.contains( 'nav-menu' ) ) {
	// 	menus.classList.add( 'nav-menu' );
	// }
	for ( const menu of menus ) {
		menu.classList.add( 'nav-menu' );
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function () {
		siteNavigation.classList.toggle( 'toggled' );

		if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
			button.setAttribute( 'aria-expanded', 'false' );
		} else {
			button.setAttribute( 'aria-expanded', 'true' );
		}
	} );

	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	document.addEventListener( 'click', function ( event ) {
		const isClickInside = siteNavigation.contains( event.target );

		if ( !isClickInside ) {
			siteNavigation.classList.remove( 'toggled' );
			button.setAttribute( 'aria-expanded', 'false' );
		}
	} );

	// Get all the link elements within the menu.
	const links = [];
	for ( const menu of menus ) {
		const link = menu.getElementsByTagName( 'a' );
		if ( link.length ) {
			links.push( ...link );
		}
	}

	// Get all the link elements with children within the menu.
	const linksWithChildren = [];
	for ( const menu of menus ) {
		const link = menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );
		if ( link.length ) {
			linksWithChildren.push( ...link );
		}
	}

	// Toggle focus each time a menu link is focused or blurred.
	for ( const link of links ) {
		link.addEventListener( 'focus', toggleFocus, true );
		link.addEventListener( 'blur', toggleFocus, true );
	}

	// Toggle focus each time a menu link with children receive a touch event.
	for ( const link of linksWithChildren ) {
		link.addEventListener( 'touchstart', toggleFocus, false );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus( event ) {
		const menuItem = this.parentNode;
		const is_focus = menuItem.classList.contains( 'focus' );
		if ( event.type === 'focus' || event.type === 'blur' ) {
			let self = this;
			// Move up through the ancestors of the current link until we hit .nav-menu.
			while ( !self.classList.contains( 'nav-menu' ) ) {
				// On li elements toggle the class .focus.
				if ( 'li' === self.tagName.toLowerCase() ) {
					self.classList.toggle( 'focus' );
				}
				self = self.parentNode;
			}
		}
		if ( !isMobile() && event.type === 'touchstart' && !is_focus ) {
			event.preventDefault();
			for ( const link of menuItem.parentNode.children ) {
				if ( menuItem !== link ) {
					link.classList.remove( 'focus' );
				}
			}
			menuItem.classList.toggle( 'focus' );
		}
	}
}() );
