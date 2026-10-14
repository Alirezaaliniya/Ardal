( function () {
	'use strict';

	function initPostTabs( root ) {
		var roots = root.matches && root.matches( '[data-ard-post-tabs]' )
			? [ root ]
			: Array.prototype.slice.call( root.querySelectorAll( '[data-ard-post-tabs]' ) );

		roots.forEach( function ( widget ) {
			if ( widget.ardPostTabsReady ) {
				return;
			}
			widget.ardPostTabsReady = true;

			var buttons = Array.prototype.slice.call( widget.querySelectorAll( '[role="tab"]' ) );
			var panels = Array.prototype.slice.call( widget.querySelectorAll( '[role="tabpanel"]' ) );

			function activate( index, focus ) {
				buttons.forEach( function ( button, buttonIndex ) {
					var active = buttonIndex === index;
					button.classList.toggle( 'is-active', active );
					button.setAttribute( 'aria-selected', active ? 'true' : 'false' );
					button.setAttribute( 'tabindex', active ? '0' : '-1' );
					if ( active && focus ) {
						button.focus();
					}
				} );

				panels.forEach( function ( panel, panelIndex ) {
					var active = panelIndex === index;
					panel.hidden = ! active;
					panel.classList.toggle( 'is-active', active );
				} );
			}

			buttons.forEach( function ( button, index ) {
				button.addEventListener( 'click', function () {
					activate( index, false );
				} );
				button.addEventListener( 'keydown', function ( event ) {
					var next = index;
					if ( 'ArrowLeft' === event.key || 'ArrowDown' === event.key ) {
						next = ( index + 1 ) % buttons.length;
					} else if ( 'ArrowRight' === event.key || 'ArrowUp' === event.key ) {
						next = ( index - 1 + buttons.length ) % buttons.length;
					} else if ( 'Home' === event.key ) {
						next = 0;
					} else if ( 'End' === event.key ) {
						next = buttons.length - 1;
					} else {
						return;
					}
					event.preventDefault();
					activate( next, true );
				} );
			} );
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			initPostTabs( document );
		} );
	} else {
		initPostTabs( document );
	}

	function registerElementorHook() {
		if ( ! window.elementorFrontend || ! window.elementorFrontend.hooks ) {
			return;
		}
		window.elementorFrontend.hooks.addAction( 'frontend/element_ready/ardal-post-feed-tabs.default', function ( $scope ) {
			initPostTabs( $scope && $scope[ 0 ] ? $scope[ 0 ] : document );
		} );
	}

	window.addEventListener( 'elementor/frontend/init', registerElementorHook );
	registerElementorHook();
}() );
