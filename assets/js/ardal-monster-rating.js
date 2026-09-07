/**
 * Nias theme — animated monster rating input.
 *
 * Replaces the WooCommerce star-rating select on the review form with a set of
 * star radios driving an SVG monster face that morphs (via GSAP MorphSVG) to the
 * chosen rating. Scoped to the `.nias-theme-rate` widget so it never touches
 * other radios on the page. Degrades to plain star radios if GSAP is missing.
 *
 * Morph path data adapted from an open monster-rating demo; all branding and
 * watermarks removed.
 *
 * @package NsTheme
 */
( function () {
	'use strict';

	function ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	ready( function () {
		var root = document.querySelector( '.nias-theme-rate' );
		if ( ! root || ! window.gsap || ! window.MorphSVGPlugin ) {
			return;
		}

		var $ = function ( s ) { return root.querySelector( s ); };
		var $$ = function ( s ) { return root.querySelectorAll( s ); };

		var inputs = $$( 'input[type="radio"]' );
		var horns = [ $( '#horn-1' ), $( '#horn-2' ), $( '#horn-3' ), $( '#horn-4' ), $( '#horn-5' ) ];
		var earL = $( '#ear-l' ), earR = $( '#ear-r' ), earring = $( '#earring' );
		var pupil = $( '#pupil' );
		var eyePaths = $$( '.eye-path' ), mouthPaths = $$( '.mouth-path' );
		var chin = $( '#chin' ), tongue = $( '#tongue' ), toothTop = $( '#tooth-top' ), toothBot = $( '#tooth-bot' );
		var avatar = $( '#nias-rate-avatar' );

		var eye0 = 'M175.4,75.3c-12,0-23.4-0.9-33.7-2.5c-4.2,6.4-6.7,14-6.7,22.2c0,12.4,5.7,23.5,14.6,30.9c6.9,5.7,15.8,9.1,25.4,9.1c9.7,0,18.5-3.4,25.4-9.1c8.9-7.3,14.6-18.4,14.6-30.9c0-8.2-2.5-15.8-6.7-22.1C198.3,74.5,187.1,75.3,175.4,75.3z';
		var eye1 = 'M213.3,82.3c-9.7,4-23.1,6.5-37.9,6.5s-28.2-2.5-37.9-6.5c-1.3,4-2.1,8.3-2.1,12.8c0,11,4.4,20.9,11.6,28.1c8.2-1.8,18-2.9,28.4-2.9s20.2,1.1,28.4,2.9c7.2-7.2,11.6-17.2,11.6-28.1C215.4,90.6,214.6,86.3,213.3,82.3z';
		var eye2 = 'M215.4,95.1c0-7.4-2-14.3-5.5-20.3c-9.3,2.5-21.3,4-34.5,4s-25.1-1.5-34.5-4c-3.5,6-5.5,12.9-5.5,20.3c0,13.1,6.3,24.7,16,32c7.2-1.1,15.4-1.7,24-1.7s16.8,0.6,24,1.7C209.1,119.8,215.4,108.2,215.4,95.1z';
		var eye3 = 'M152.9,128.2c6.8-0.9,14.5-1.5,22.5-1.5s15.7,0.5,22.5,1.5c10.6-7.2,17.5-19.3,17.5-33.1c0-12.8-6-24.2-15.4-31.5c-7.4,2-15.7,3.2-24.6,3.2s-17.3-1.2-24.6-3.2c-9.4,7.3-15.4,18.7-15.4,31.5C135.4,108.8,142.3,121,152.9,128.2z';
		var eye4 = 'M150.7,128.1c7.3-1.1,15.6-1.7,24.3-1.7s17,0.6,24.3,1.7c10.1-7.5,16.7-19.5,16.7-33c0-13.4-6.4-25.3-16.4-32.8c-6.9-5.2-15.4-8.2-24.6-8.2c-9.5,0-18.2,3.2-25.2,8.6c-9.6,7.5-15.8,19.2-15.8,32.4C134,108.6,140.6,120.6,150.7,128.1z';
		var eye5 = 'M219,95.1c0,12-4.7,23-12.4,30.9c-7.9,8.1-19,13.1-31.6,13.1c-12.2,0-23.2-4.9-31.1-12.9c-8-8-12.9-19-12.9-31.1c0-13.7,6.2-25.9,16-34c7.6-6.3,17.4-10,28-10c9.9,0,19.1,3.3,26.4,8.8C212.1,67.9,219,80.7,219,95.1z';
		var mouth0 = 'M174.6,178.2h5.1H215c0,0,0.1,0,0.1,0c0,0,0,0-0.1,0h-35.3L174.6,178.2l-4.6,0.1h-35c0,0-0.1,0-0.1,0c0,0,0,0,0.1,0h35H174.6z';
		var mouth1 = 'M175,172.6h7c8.9,0,16.2,6.4,17.7,14.9c0.2,1,0.3,2,0.3,3.1c0,0.8-0.5,2-2,2l-16,0h-7h-7l-16,0c-1.4,0-2-1.2-2-2c0-1,0.1-1.9,0.2-2.8c1.4-8.6,8.8-15.1,17.7-15.1H175z';
		var mouth2 = 'M175,187c5.5,0,9.7-2.1,13.8-4.1c5.5-2.7,11.3-5.5,17.6-0.4c0.1,0.1,0.3,0.3,0.2,0.4c-0.1,0.1-0.3,0.1-0.4,0c-5.6-5.1-11.7-2.6-17.4,0.2c-4.2,2-7.3,4.1-13.9,4.1c-6.5,0-9-2.1-12.9-4.1c-5.1-2.8-10.6-5.8-18.6-0.6c-0.1,0-0.1,0-0.1,0c0,0,0-0.1,0.1-0.1c7.9-5.4,13.4-2.4,18.8,0.5C165.9,185,169.7,187,175,187z';
		var mouth3 = 'M175,179.8c2,0,4-0.1,6-0.2c11-0.9,22-4.1,32.9-9.7c0.1,0,0.2-0.1,0.2,0c0,0.1-0.1,0.2-0.2,0.2c-11.6,6-21.7,9-33,9.7c-2,0.1-4,0.3-6,0.3c-2.3,0-4.6-0.1-6.8-0.3c-10.9-0.8-20.8-3.9-32.1-9.7c-0.1,0-0.1-0.1-0.1-0.1c0,0,0.1,0,0.2,0c10.7,5.5,21.4,8.7,32.1,9.6C170.5,179.7,172.7,179.8,175,179.8z';
		var mouth4 = 'M174.8,172.6h10H200l0,4.9c0,1.4-0.2,2.7-0.6,4c-1.8,5.9-7.5,10.1-14.3,10.1h-10.4h-9.9c-6.7,0-12.3-4.2-14.2-9.9c-0.4-1.3-0.7-2.7-0.7-4.2v-4.9h14.3H174.8z';
		var mouth5 = 'M175,165.6h4.8l23.3,0c5.6,0,8.9,4.7,8.9,8.9c0,3-0.4,6-1.2,8.7c-3.8,14-16.7,24.3-31.9,24.3H175h-3.9c-15.5,0-28.6-10.7-32.1-25.2c-0.6-2.5-0.9-5.2-0.9-7.9c0-5,4.1-8.9,8.9-8.9l23.9,0H175z';

		var curRating = 0;
		var dur = 0.5;
		var mq = window.matchMedia( '(prefers-reduced-motion: reduce)' );
		var setDur = function () { dur = mq.matches ? 0 : 0.5; };
		if ( mq.addEventListener ) { mq.addEventListener( 'change', setDur ); } else if ( mq.addListener ) { mq.addListener( setDur ); }
		setDur();

		gsap.registerPlugin( MorphSVGPlugin );

		// The widget lives inside the product "reviews" tab, which is display:none
		// until opened. GSAP resolves the px-based transformOrigins (ears/horns)
		// from getBBox(), which returns zeros while the element is hidden — that
		// breaks the right-ear rotation origin and skews the whole face. So defer
		// the baseline `gsap.set()` calls until the avatar is actually rendered.
		var initialized = false;
		function baseline() {
			if ( initialized ) {
				return;
			}
			initialized = true;
			gsap.set( earL, { transformOrigin: '40px 40px', rotate: '-15deg', y: 10, scale: 0.9 } );
			gsap.set( earR, { transformOrigin: '40px 40px', rotate: '15deg', y: 10, scale: 0.9 } );
			gsap.set( earring, { transformOrigin: '50% 0', x: -12, y: 8 } );
			gsap.set( pupil, { transformOrigin: '50% 50%' } );
			gsap.set( eyePaths, { morphSVG: eye0 } );
			gsap.set( mouthPaths, { morphSVG: mouth0 } );
			gsap.set( chin, { y: 0 } );
			gsap.set( horns[ 0 ], { transformOrigin: '20px 45px', scale: 0 } );
			gsap.set( horns[ 1 ], { transformOrigin: '12px 45px', scale: 0 } );
			gsap.set( horns[ 2 ], { transformOrigin: '32px 38px', scale: 0 } );
			gsap.set( horns[ 3 ], { transformOrigin: '12px 38px', scale: 0 } );
			gsap.set( horns[ 4 ], { transformOrigin: '50% 90%', scale: 0 } );
			gsap.set( avatar, { opacity: 1 } );
		}

		// Run baseline the first time the avatar becomes visible (tab opened /
		// scrolled into view); fall back to running it immediately if there's no
		// IntersectionObserver support.
		if ( 'IntersectionObserver' in window ) {
			var io = new IntersectionObserver( function ( entries, obs ) {
				for ( var k = 0; k < entries.length; k++ ) {
					if ( entries[ k ].isIntersecting ) {
						obs.disconnect();
						baseline();
						break;
					}
				}
			} );
			io.observe( avatar );
		} else {
			baseline();
		}

		var props = {
			0: { eye: eye0, mouth: mouth0, chinY: 0, pupilS: 1, earS: 0.9, earY: 10, rotL: '-15deg', rotR: '15deg', erX: -12, erY: 8, tY: 0, ttY: 0, tbY: 0 },
			1: { eye: eye1, mouth: mouth1, chinY: 3, pupilS: 0.84, earS: 1.1, earY: -4, rotL: '10deg', rotR: '-10deg', erX: 0, erY: 0, tY: 2, ttY: 5, tbY: -17 },
			2: { eye: eye2, mouth: mouth2, chinY: -2, pupilS: 0.94, earS: 1.05, earY: -2, rotL: '5deg', rotR: '-5deg', erX: -2, erY: -1, tY: 0, ttY: 0, tbY: 0 },
			3: { eye: eye3, mouth: mouth3, chinY: -4, pupilS: 1, earS: 1, earY: 0, rotL: '0deg', rotR: '0deg', erX: -4, erY: -2, tY: 0, ttY: 0, tbY: 0 },
			4: { eye: eye4, mouth: mouth4, chinY: 3, pupilS: 1.1, earS: 0.95, earY: -3, rotL: '2deg', rotR: '-2deg', erX: -2, erY: -5, tY: -4, ttY: 6, tbY: -16 },
			5: { eye: eye5, mouth: mouth5, chinY: 14, pupilS: 1.2, earS: 0.9, earY: -6, rotL: '4deg', rotR: '-4deg', erX: 0, erY: -10, tY: 0, ttY: 2, tbY: -2 }
		};

		function onPick( e ) {
			baseline(); // Ensure correct transform origins are set before the first morph.
			var num = parseInt( e.target.getAttribute( 'data-num' ), 10 ) || 0;
			var p = props[ num ];

			var hornsU = horns.slice( 0, num );
			if ( hornsU.length > curRating ) { hornsU = hornsU.slice( curRating ); }
			var hornsD = horns.slice( num, curRating );

			var tl = gsap.timeline( { paused: true, defaults: { duration: dur, ease: 'sine.out' } } );
			tl.to( eyePaths, { morphSVG: p.eye }, 0 )
				.to( mouthPaths, { morphSVG: p.mouth }, 0 )
				.to( chin, { y: p.chinY }, 0 )
				.to( pupil, { scale: p.pupilS }, 0 )
				.to( earL, { scale: p.earS, y: p.earY, rotate: p.rotL }, 0 )
				.to( earR, { scale: p.earS, y: p.earY, rotate: p.rotR }, 0 )
				.to( earring, { x: p.erX, y: p.erY }, 0 )
				.to( tongue, { y: p.tY }, 0 )
				.to( toothTop, { y: p.ttY }, 0 )
				.to( toothBot, { y: p.tbY }, 0 );

			if ( hornsU.length ) {
				if ( dur ) {
					gsap.to( hornsU, { scale: 1, stagger: { each: 0.1, ease: 'power1.out' }, duration: dur, ease: 'back.out' } );
				} else {
					gsap.set( hornsU, { scale: 1 } );
				}
			}
			if ( hornsD.length ) {
				if ( dur ) {
					gsap.to( hornsD, { scale: 0, stagger: { each: -0.08, ease: 'power2.out' }, duration: dur, ease: 'back.in' } );
				} else {
					gsap.set( hornsD, { scale: 0 } );
				}
			}

			curRating = num;
			tl.play();
		}

		Array.prototype.forEach.call( inputs, function ( i ) {
			i.addEventListener( 'click', onPick );
		} );
	} );
}() );
