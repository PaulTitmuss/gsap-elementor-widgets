/**
 * GSAP Elementor Widgets — frontend initialisation.
 *
 * All GSAP initialisation logic lives here, wrapped in a namespace to avoid
 * global scope conflicts. Each widget renders a container with a
 * `data-gsap-type` attribute and a JSON `data-gsap` config attribute that this
 * script reads to configure the animation.
 *
 * @package GSAP_Elementor_Widgets
 */
( function () {
        'use strict';

        /**
         * Main namespace object.
         */
        var GSAPEW = {

                /**
                 * Whether GSAP + ScrollTrigger are available.
                 *
                 * @return {boolean} True when GSAP is ready.
                 */
                isReady: function () {
                        return typeof window.gsap !== 'undefined';
                },

                /**
                 * Register ScrollTrigger if present.
                 *
                 * @return {boolean} True when ScrollTrigger is registered.
                 */
                hasScrollTrigger: function () {
                        if ( typeof window.ScrollTrigger !== 'undefined' ) {
                                if ( ! GSAPEW._stRegistered ) {
                                        window.gsap.registerPlugin( window.ScrollTrigger );
                                        GSAPEW._stRegistered = true;
                                }
                                return true;
                        }
                        return false;
                },

                _stRegistered: false,

                /**
                 * Safely parse a JSON config attribute.
                 *
                 * @param {Element} el The element carrying the data-gsap attribute.
                 * @return {Object} Parsed config or empty object.
                 */
                parseConfig: function ( el ) {
                        var raw = el.getAttribute( 'data-gsap' );
                        if ( ! raw ) {
                                return {};
                        }
                        try {
                                return JSON.parse( raw );
                        } catch ( e ) {
                                return {};
                        }
                },

                /**
                 * Build the ScrollTrigger config object for an element.
                 *
                 * @param {Element} el     Trigger element.
                 * @param {boolean} repeat Whether to replay on each scroll.
                 * @param {Object}  extra  Extra ScrollTrigger properties.
                 * @return {Object} ScrollTrigger config.
                 */
                scrollTriggerConfig: function ( el, repeat, extra ) {
                        var config = {
                                trigger: el,
                                start: 'top 85%',
                                toggleActions: repeat ? 'restart none none reset' : 'play none none none',
                        };
                        if ( repeat ) {
                                config.toggleActions = 'restart none none reverse';
                        }
                        if ( extra ) {
                                for ( var key in extra ) {
                                        if ( Object.prototype.hasOwnProperty.call( extra, key ) ) {
                                                config[ key ] = extra[ key ];
                                        }
                                }
                        }
                        return config;
                },

                /**
                 * Initialise every widget on the page.
                 *
                 * @param {Element|Document} scope Root to search within.
                 * @return {void}
                 */
                initAll: function ( scope ) {
                        if ( ! GSAPEW.isReady() ) {
                                return;
                        }
                        scope = scope || document;

                        var handlers = {
                                'animated-heading': GSAPEW.initAnimatedHeading,
                                'scroll-counter': GSAPEW.initScrollCounter,
                                'parallax-section': GSAPEW.initParallax,
                                'staggered-grid': GSAPEW.initStaggeredGrid,
                                'timeline-reveal': GSAPEW.initTimeline,
                                'animated-text': GSAPEW.initAnimatedText,
                                'icon-box-3d': GSAPEW.initIconBox3D,
                                'reveal-on-scroll': GSAPEW.initRevealOnScroll,
                                'svg-animator': GSAPEW.initSvgAnimator,
                                'hero-bento': GSAPEW.initHeroBento,
                        };

                        Object.keys( handlers ).forEach( function ( type ) {
                                var nodes = scope.querySelectorAll( '[data-gsap-type="' + type + '"]' );
                                nodes.forEach( function ( el ) {
                                        if ( el.getAttribute( 'data-gsap-init' ) === '1' ) {
                                                return;
                                        }
                                        el.setAttribute( 'data-gsap-init', '1' );
                                        try {
                                                handlers[ type ]( el, GSAPEW.parseConfig( el ) );
                                        } catch ( e ) {
                                                // Fail gracefully — never break the page.
                                                if ( window.console && window.console.warn ) {
                                                        window.console.warn( 'GSAP EW init error (' + type + '):', e );
                                                }
                                        }
                                } );
                        } );
                },

                /* =============================================================
                 * 1. Animated Heading
                 * =========================================================== */
                initAnimatedHeading: function ( el, cfg ) {
                        var gsap = window.gsap;
                        var duration = cfg.duration || 1;
                        var delay = cfg.delay || 0;
                        var ease = cfg.easing || 'power2.out';
                        var onScroll = cfg.trigger === 'scroll';
                        var repeat = !! cfg.repeat;

                        var build = function () {
                                var tl;

                                switch ( cfg.animation ) {
                                        case 'slide-up':
                                                gsap.set( el, { opacity: 0, y: 40 } );
                                                tl = gsap.to( el, {
                                                        opacity: 1,
                                                        y: 0,
                                                        duration: duration,
                                                        delay: delay,
                                                        ease: ease,
                                                        paused: onScroll,
                                                } );
                                                break;

                                        case 'split-text':
                                                GSAPEW._splitIntoChars( el );
                                                var chars = el.querySelectorAll( '.gsap-ew-char' );
                                                gsap.set( chars, { opacity: 0, y: 30 } );
                                                tl = gsap.to( chars, {
                                                        opacity: 1,
                                                        y: 0,
                                                        duration: duration,
                                                        delay: delay,
                                                        ease: ease,
                                                        stagger: cfg.stagger || 0.04,
                                                        paused: onScroll,
                                                } );
                                                break;

                                        case 'typewriter':
                                                tl = GSAPEW._typewriter( el, duration, delay, onScroll );
                                                break;

                                        case 'fade-in':
                                        default:
                                                gsap.set( el, { opacity: 0 } );
                                                tl = gsap.to( el, {
                                                        opacity: 1,
                                                        duration: duration,
                                                        delay: delay,
                                                        ease: ease,
                                                        paused: onScroll,
                                                } );
                                                break;
                                }

                                return tl;
                        };

                        var tween = build();

                        if ( onScroll && GSAPEW.hasScrollTrigger() && tween ) {
                                window.ScrollTrigger.create(
                                        GSAPEW.scrollTriggerConfig( el, repeat, {
                                                animation: tween,
                                        } )
                                );
                        }
                },

                /**
                 * Split element text into per-character spans (preserving spaces).
                 *
                 * @param {Element} el Target element.
                 * @return {void}
                 */
                _splitIntoChars: function ( el ) {
                        if ( el.getAttribute( 'data-gsap-split' ) === '1' ) {
                                return;
                        }
                        var text = el.textContent;
                        var frag = document.createDocumentFragment();
                        for ( var i = 0; i < text.length; i++ ) {
                                var ch = text.charAt( i );
                                var span = document.createElement( 'span' );
                                span.className = 'gsap-ew-char';
                                span.style.display = 'inline-block';
                                span.style.whiteSpace = 'pre';
                                span.textContent = ch;
                                frag.appendChild( span );
                        }
                        el.textContent = '';
                        el.appendChild( frag );
                        el.setAttribute( 'data-gsap-split', '1' );
                },

                /**
                 * Typewriter effect using the GSAP TextPlugin when available, with a
                 * manual fallback otherwise.
                 *
                 * @param {Element} el       Target element.
                 * @param {number}  duration Duration in seconds.
                 * @param {number}  delay    Delay in seconds.
                 * @param {boolean} paused   Whether to start paused (scroll trigger).
                 * @return {Object} GSAP tween/timeline.
                 */
                _typewriter: function ( el, duration, delay, paused ) {
                        var gsap = window.gsap;
                        var fullText = el.textContent;
                        el.textContent = '';
                        el.classList.add( 'gsap-ew-typewriter' );

                        if ( typeof window.TextPlugin !== 'undefined' ) {
                                gsap.registerPlugin( window.TextPlugin );
                                return gsap.to( el, {
                                        duration: duration,
                                        delay: delay,
                                        text: { value: fullText, delimiter: '' },
                                        ease: 'none',
                                        paused: paused,
                                } );
                        }

                        // Fallback: tween a counter and slice the string.
                        var obj = { count: 0 };
                        return gsap.to( obj, {
                                count: fullText.length,
                                duration: duration,
                                delay: delay,
                                ease: 'none',
                                paused: paused,
                                onUpdate: function () {
                                        el.textContent = fullText.substring( 0, Math.round( obj.count ) );
                                },
                        } );
                },

                /* =============================================================
                 * 2. Scroll Counter
                 * =========================================================== */
                initScrollCounter: function ( el, cfg ) {
                        var gsap = window.gsap;
                        var numberEl = el.querySelector( '.gsap-ew-counter-number' );
                        if ( ! numberEl ) {
                                return;
                        }

                        var start = typeof cfg.start === 'number' ? cfg.start : 0;
                        var end = typeof cfg.end === 'number' ? cfg.end : 0;
                        var duration = cfg.duration || 2;
                        var decimals = cfg.decimals || 0;
                        var separator = cfg.separator || '';
                        var repeat = !! cfg.repeat;

                        var format = function ( value ) {
                                var fixed = Number( value ).toFixed( decimals );
                                if ( separator ) {
                                        var parts = fixed.split( '.' );
                                        parts[ 0 ] = parts[ 0 ].replace( /\B(?=(\d{3})+(?!\d))/g, separator );
                                        fixed = parts.join( '.' );
                                }
                                return fixed;
                        };

                        var counter = { val: start };
                        numberEl.textContent = format( start );

                        var tween = gsap.to( counter, {
                                val: end,
                                duration: duration,
                                ease: 'power1.out',
                                paused: true,
                                onUpdate: function () {
                                        numberEl.textContent = format( counter.val );
                                },
                        } );

                        if ( GSAPEW.hasScrollTrigger() ) {
                                window.ScrollTrigger.create( {
                                        trigger: el,
                                        start: 'top 85%',
                                        onEnter: function () {
                                                tween.restart();
                                        },
                                        onEnterBack: function () {
                                                if ( repeat ) {
                                                        tween.restart();
                                                }
                                        },
                                        onLeaveBack: function () {
                                                if ( repeat ) {
                                                        counter.val = start;
                                                        numberEl.textContent = format( start );
                                                        tween.pause( 0 );
                                                }
                                        },
                                } );
                        } else {
                                tween.play();
                        }
                },

                /* =============================================================
                 * 3. Parallax Section
                 * =========================================================== */
                initParallax: function ( el, cfg ) {
                        var gsap = window.gsap;
                        if ( ! GSAPEW.hasScrollTrigger() ) {
                                return;
                        }

                        var speed = typeof cfg.speed === 'number' ? cfg.speed : 0.3;
                        if ( speed === 0 ) {
                                return; // No movement requested.
                        }

                        var horizontal = cfg.direction === 'horizontal';
                        var scrub = cfg.scrub !== false;

                        // Target: the background layer or the inner content.
                        var target = cfg.applyTo === 'content'
                                ? el.querySelector( '.gsap-ew-parallax-inner' )
                                : el.querySelector( '.gsap-ew-parallax-bg' );

                        if ( ! target ) {
                                target = el.querySelector( '.gsap-ew-parallax-inner' );
                        }
                        if ( ! target ) {
                                return;
                        }

                        // Movement distance scaled by element height and speed.
                        var distance = el.offsetHeight * speed;
                        var fromVars = {};
                        var toVars = {
                                ease: 'none',
                                scrollTrigger: {
                                        trigger: el,
                                        start: 'top bottom',
                                        end: 'bottom top',
                                        scrub: scrub ? 1 : false,
                                },
                        };

                        if ( horizontal ) {
                                fromVars.x = -distance;
                                toVars.x = distance;
                        } else {
                                fromVars.y = -distance;
                                toVars.y = distance;
                        }

                        gsap.fromTo( target, fromVars, toVars );
                },

                /* =============================================================
                 * 4. Staggered Card Grid
                 * =========================================================== */
                initStaggeredGrid: function ( el, cfg ) {
                        var gsap = window.gsap;
                        var cards = el.querySelectorAll( '.gsap-ew-card-item' );
                        if ( ! cards.length ) {
                                return;
                        }

                        var duration = cfg.duration || 0.8;
                        var stagger = typeof cfg.stagger === 'number' ? cfg.stagger : 0.15;
                        var ease = cfg.easing || 'power2.out';
                        var repeat = !! cfg.repeat;

                        var fromVars = { opacity: 0 };
                        switch ( cfg.animation ) {
                                case 'fade-in':
                                        break;
                                case 'zoom-in':
                                        fromVars.scale = 0.8;
                                        break;
                                case 'slide-left':
                                        fromVars.x = -60;
                                        break;
                                case 'slide-right':
                                        fromVars.x = 60;
                                        break;
                                case 'fade-up':
                                default:
                                        fromVars.y = 50;
                                        break;
                        }

                        gsap.set( cards, fromVars );

                        var toVars = {
                                opacity: 1,
                                x: 0,
                                y: 0,
                                scale: 1,
                                duration: duration,
                                ease: ease,
                                stagger: stagger,
                                paused: true,
                        };

                        var tween = gsap.to( cards, toVars );

                        if ( GSAPEW.hasScrollTrigger() ) {
                                window.ScrollTrigger.create( {
                                        trigger: el,
                                        start: 'top 85%',
                                        onEnter: function () {
                                                tween.restart();
                                        },
                                        onLeaveBack: function () {
                                                if ( repeat ) {
                                                        gsap.set( cards, fromVars );
                                                        tween.pause( 0 );
                                                }
                                        },
                                        onEnterBack: function () {
                                                if ( repeat ) {
                                                        tween.restart();
                                                }
                                        },
                                } );
                        } else {
                                tween.play();
                        }
                },

                /* =============================================================
                 * 5. Timeline Reveal
                 * =========================================================== */
                initTimeline: function ( el, cfg ) {
                        var gsap = window.gsap;
                        var items = el.querySelectorAll( '.gsap-ew-timeline-item' );
                        if ( ! items.length ) {
                                return;
                        }

                        var duration = cfg.duration || 0.7;
                        var stagger = typeof cfg.stagger === 'number' ? cfg.stagger : 0.2;
                        var ease = cfg.easing || 'power2.out';
                        var repeat = !! cfg.repeat;
                        var horizontal = cfg.layout === 'horizontal';

                        // Set initial state per item based on its side / layout.
                        items.forEach( function ( item ) {
                                var vars = { opacity: 0 };
                                if ( horizontal ) {
                                        vars.y = 40;
                                } else if ( item.classList.contains( 'gsap-ew-timeline-item--right' ) ) {
                                        vars.x = 60;
                                } else {
                                        vars.x = -60;
                                }
                                gsap.set( item, vars );
                        } );

                        var animateIn = function () {
                                gsap.to( items, {
                                        opacity: 1,
                                        x: 0,
                                        y: 0,
                                        duration: duration,
                                        ease: ease,
                                        stagger: stagger,
                                } );
                        };

                        var reset = function () {
                                items.forEach( function ( item ) {
                                        var vars = { opacity: 0 };
                                        if ( horizontal ) {
                                                vars.y = 40;
                                        } else if ( item.classList.contains( 'gsap-ew-timeline-item--right' ) ) {
                                                vars.x = 60;
                                        } else {
                                                vars.x = -60;
                                        }
                                        gsap.set( item, vars );
                                } );
                        };

                        if ( GSAPEW.hasScrollTrigger() ) {
                                window.ScrollTrigger.create( {
                                        trigger: el,
                                        start: 'top 80%',
                                        onEnter: animateIn,
                                        onEnterBack: function () {
                                                if ( repeat ) {
                                                        animateIn();
                                                }
                                        },
                                        onLeaveBack: function () {
                                                if ( repeat ) {
                                                        reset();
                                                }
                                        },
                                } );
                        } else {
                                animateIn();
                        }
                },

                /* =============================================================
                 * 6. Animated Text (rich / body copy)
                 * =========================================================== */
                initAnimatedText: function ( el, cfg ) {
                        var gsap = window.gsap;
                        var duration = cfg.duration || 0.9;
                        var delay = cfg.delay || 0;
                        var ease = cfg.easing || 'power2.out';
                        var stagger = typeof cfg.stagger === 'number' ? cfg.stagger : 0.06;
                        var onScroll = cfg.trigger === 'scroll';
                        var repeat = !! cfg.repeat;
                        var anim = cfg.animation || 'fade-up';

                        var targets = el;
                        var fromVars = { opacity: 0 };
                        var toVars = { opacity: 1, duration: duration, delay: delay, ease: ease, paused: onScroll };
                        var splitMode = ( anim === 'words' || anim === 'lines' || anim === 'chars' );

                        if ( splitMode ) {
                                GSAPEW._splitText( el, anim );
                                var selector = anim === 'chars' ? '.gsap-ew-char' : ( anim === 'words' ? '.gsap-ew-word' : '.gsap-ew-line' );
                                targets = el.querySelectorAll( selector );

                                // Direction each unit travels from as it appears.
                                var dir = cfg.direction || 'up';
                                fromVars = { opacity: 0 };
                                toVars = { opacity: 1, duration: duration, delay: delay, ease: ease, paused: onScroll };
                                switch ( dir ) {
                                        case 'down':
                                                fromVars.y = -24;
                                                toVars.y = 0;
                                                break;
                                        case 'left':
                                                fromVars.x = -40;
                                                toVars.x = 0;
                                                break;
                                        case 'right':
                                                fromVars.x = 40;
                                                toVars.x = 0;
                                                break;
                                        case 'scale':
                                                fromVars.scale = 0.5;
                                                toVars.scale = 1;
                                                break;
                                        case 'none':
                                                break;
                                        case 'up':
                                        default:
                                                fromVars.y = 24;
                                                toVars.y = 0;
                                                break;
                                }

                                // Order the units appear in (GSAP stagger "from").
                                var order = cfg.order || 'start';
                                if ( order === 'random' ) {
                                        toVars.stagger = { each: stagger, from: 'random' };
                                } else if ( order === 'end' || order === 'center' || order === 'edges' ) {
                                        toVars.stagger = { each: stagger, from: ( order === 'edges' ? 'edges' : order ) };
                                } else {
                                        toVars.stagger = stagger;
                                }
                        } else {
                                switch ( anim ) {
                                        case 'fade-up':
                                                fromVars.y = 40;
                                                toVars.y = 0;
                                                break;
                                        case 'slide-left':
                                                fromVars.x = -60;
                                                toVars.x = 0;
                                                break;
                                        case 'slide-right':
                                                fromVars.x = 60;
                                                toVars.x = 0;
                                                break;
                                        case 'blur-in':
                                                fromVars.filter = 'blur(12px)';
                                                toVars.filter = 'blur(0px)';
                                                break;
                                        case 'fade-in':
                                        default:
                                                break;
                                }
                        }

                        gsap.set( targets, fromVars );
                        var tween = gsap.to( targets, toVars );

                        if ( onScroll && GSAPEW.hasScrollTrigger() ) {
                                window.ScrollTrigger.create(
                                        GSAPEW.scrollTriggerConfig( el, repeat, { animation: tween } )
                                );
                        }
                },

                /**
                 * Split an element's text into words or lines (chars reuses the
                 * existing per-character splitter). Preserves inline HTML poorly, so it
                 * targets the concatenated text — ideal for paragraphs / headings.
                 *
                 * @param {Element} el   Target element.
                 * @param {string}  mode 'words' | 'lines' | 'chars'.
                 * @return {void}
                 */
                _splitText: function ( el, mode ) {
                        if ( el.getAttribute( 'data-gsap-split' ) === '1' ) {
                                return;
                        }
                        if ( mode === 'chars' ) {
                                GSAPEW._splitIntoChars( el );
                                return;
                        }
                        var text = el.textContent.replace( /\s+/g, ' ' ).trim();
                        var units = mode === 'lines' ? text.split( /(?<=[.!?])\s+/ ) : text.split( ' ' );
                        var cls = mode === 'lines' ? 'gsap-ew-line' : 'gsap-ew-word';
                        var frag = document.createDocumentFragment();
                        units.forEach( function ( unit, i ) {
                                var span = document.createElement( 'span' );
                                span.className = cls;
                                span.style.display = mode === 'lines' ? 'block' : 'inline-block';
                                span.style.willChange = 'opacity, transform';
                                span.textContent = unit;
                                frag.appendChild( span );
                                if ( mode === 'words' && i < units.length - 1 ) {
                                        frag.appendChild( document.createTextNode( ' ' ) );
                                }
                        } );
                        el.textContent = '';
                        el.appendChild( frag );
                        el.setAttribute( 'data-gsap-split', '1' );
                },

                /* =============================================================
                 * 7. 3D Icon Box
                 * =========================================================== */
                initIconBox3D: function ( el, cfg ) {
                        var gsap = window.gsap;
                        var inner = el.querySelector( '.gsap-ew-iconbox-inner' );
                        if ( ! inner ) {
                                return;
                        }

                        var duration = cfg.duration || 1;
                        var delay = cfg.delay || 0;
                        var ease = cfg.easing || 'power3.out';
                        var onScroll = cfg.trigger === 'scroll';
                        var repeat = !! cfg.repeat;

                        var fromVars = { opacity: 0, transformPerspective: 800 };
                        switch ( cfg.animation ) {
                                case 'flip-y':
                                        fromVars.rotationY = -90;
                                        break;
                                case 'rotate-3d':
                                        fromVars.rotationX = 45;
                                        fromVars.rotationY = -45;
                                        fromVars.z = -150;
                                        break;
                                case 'zoom-rotate':
                                        fromVars.scale = 0.4;
                                        fromVars.rotationZ = -25;
                                        break;
                                case 'swing':
                                        fromVars.rotationX = -80;
                                        fromVars.transformOrigin = 'top center';
                                        break;
                                case 'unfold':
                                        fromVars.rotationX = -90;
                                        fromVars.transformOrigin = 'top center';
                                        fromVars.y = 20;
                                        break;
                                case 'flip-x':
                                default:
                                        fromVars.rotationX = -90;
                                        break;
                        }

                        gsap.set( inner, fromVars );

                        var tween = gsap.to( inner, {
                                opacity: 1,
                                rotationX: 0,
                                rotationY: 0,
                                rotationZ: 0,
                                scale: 1,
                                z: 0,
                                y: 0,
                                duration: duration,
                                delay: delay,
                                ease: ease,
                                paused: onScroll,
                        } );

                        if ( onScroll && GSAPEW.hasScrollTrigger() ) {
                                window.ScrollTrigger.create(
                                        GSAPEW.scrollTriggerConfig( el, repeat, { animation: tween } )
                                );
                        }

                        // Optional 3D hover tilt following the cursor.
                        if ( cfg.hoverTilt ) {
                                GSAPEW._bindTilt( el, inner );
                        }
                },

                /**
                 * Bind a cursor-following 3D tilt to an element.
                 *
                 * @param {Element} bounds Element used for pointer bounds.
                 * @param {Element} target Element that receives the transform.
                 * @return {void}
                 */
                _bindTilt: function ( bounds, target ) {
                        var gsap = window.gsap;
                        var max = 12;
                        bounds.addEventListener( 'mousemove', function ( e ) {
                                var rect = bounds.getBoundingClientRect();
                                var px = ( e.clientX - rect.left ) / rect.width - 0.5;
                                var py = ( e.clientY - rect.top ) / rect.height - 0.5;
                                gsap.to( target, {
                                        rotationY: px * max,
                                        rotationX: -py * max,
                                        duration: 0.4,
                                        ease: 'power2.out',
                                        transformPerspective: 800,
                                } );
                        } );
                        bounds.addEventListener( 'mouseleave', function () {
                                gsap.to( target, { rotationX: 0, rotationY: 0, duration: 0.6, ease: 'power3.out' } );
                        } );
                },

                /* =============================================================
                 * 8. Reveal on Scroll (text or image)
                 * =========================================================== */
                initRevealOnScroll: function ( el, cfg ) {
                        var gsap = window.gsap;
                        var target = el.querySelector( '.gsap-ew-reveal-target' );
                        var clip = el.querySelector( '.gsap-ew-reveal-clip' );
                        if ( ! target ) {
                                return;
                        }

                        var duration = cfg.duration || 1.1;
                        var delay = cfg.delay || 0;
                        var ease = cfg.easing || 'power3.out';
                        var dir = cfg.direction || 'left';
                        var scrub = !! cfg.scrub;
                        var repeat = !! cfg.repeat;
                        var start = cfg.start || 'top 85%';
                        var hasST = GSAPEW.hasScrollTrigger();

                        var fromVars = {};
                        var toVars = { ease: ease };

                        switch ( cfg.animation ) {
                                case 'fade-slide':
                                        fromVars.opacity = 0;
                                        toVars.opacity = 1;
                                        if ( dir === 'left' ) { fromVars.x = -80; toVars.x = 0; }
                                        else if ( dir === 'right' ) { fromVars.x = 80; toVars.x = 0; }
                                        else if ( dir === 'top' ) { fromVars.y = -80; toVars.y = 0; }
                                        else { fromVars.y = 80; toVars.y = 0; }
                                        break;

                                case 'zoom':
                                        if ( clip ) { clip.style.overflow = 'hidden'; }
                                        fromVars.scale = 1.3;
                                        fromVars.opacity = 0;
                                        toVars.scale = 1;
                                        toVars.opacity = 1;
                                        break;

                                case 'blur':
                                        fromVars.opacity = 0;
                                        fromVars.filter = 'blur(16px)';
                                        toVars.opacity = 1;
                                        toVars.filter = 'blur(0px)';
                                        break;

                                case 'clip':
                                default:
                                        if ( clip ) { clip.style.overflow = 'hidden'; }
                                        var insets = {
                                                left:   'inset(0% 100% 0% 0%)',
                                                right:  'inset(0% 0% 0% 100%)',
                                                top:    'inset(0% 0% 100% 0%)',
                                                bottom: 'inset(100% 0% 0% 0%)',
                                        };
                                        fromVars.clipPath = insets[ dir ] || insets.left;
                                        fromVars.webkitClipPath = fromVars.clipPath;
                                        toVars.clipPath = 'inset(0% 0% 0% 0%)';
                                        toVars.webkitClipPath = toVars.clipPath;
                                        break;
                        }

                        gsap.set( target, fromVars );

                        if ( scrub && hasST ) {
                                toVars.scrollTrigger = {
                                        trigger: el,
                                        start: start,
                                        end: 'bottom center',
                                        scrub: 1,
                                };
                                gsap.to( target, toVars );
                                return;
                        }

                        toVars.duration = duration;
                        toVars.delay = delay;
                        toVars.paused = hasST;
                        var tween = gsap.to( target, toVars );

                        if ( hasST ) {
                                window.ScrollTrigger.create(
                                        GSAPEW.scrollTriggerConfig( el, repeat, { animation: tween, start: start } )
                                );
                        } else {
                                tween.play();
                        }
                },

                /* =============================================================
                 * 9. SVG Animator
                 * =========================================================== */
                initSvgAnimator: function ( el, cfg ) {
                        var gsap = window.gsap;
                        var svg = el.querySelector( 'svg' );
                        if ( ! svg ) {
                                return;
                        }

                        var duration = cfg.duration || 2;
                        var stagger = typeof cfg.stagger === 'number' ? cfg.stagger : 0.2;
                        var delay = cfg.delay || 0;
                        var ease = cfg.easing || 'power2.inOut';
                        var onScroll = cfg.trigger === 'scroll';
                        var loop = !! cfg.loop;
                        var repeat = !! cfg.repeat;
                        var anim = cfg.animation || 'draw';

                        var shapes = svg.querySelectorAll( 'path, line, polyline, polygon, circle, rect, ellipse' );
                        if ( ! shapes.length ) {
                                return;
                        }

                        var build = function () {
                                var tl = gsap.timeline( {
                                        paused: onScroll,
                                        repeat: loop ? -1 : 0,
                                        repeatDelay: loop ? 0.6 : 0,
                                        delay: delay,
                                } );

                                if ( anim === 'draw' ) {
                                        var fills = [];
                                        shapes.forEach( function ( shape ) {
                                                var len = GSAPEW._pathLength( shape );
                                                if ( ! len ) {
                                                        return;
                                                }
                                                // Ensure a visible stroke exists to draw.
                                                var stroke = window.getComputedStyle( shape ).stroke;
                                                if ( ! stroke || stroke === 'none' || stroke === 'rgba(0, 0, 0, 0)' ) {
                                                        shape.style.stroke = 'currentColor';
                                                }
                                                if ( ! shape.getAttribute( 'stroke-width' ) && ! shape.style.strokeWidth ) {
                                                        shape.style.strokeWidth = '2';
                                                }
                                                gsap.set( shape, {
                                                        strokeDasharray: len,
                                                        strokeDashoffset: len,
                                                } );
                                                // Hide fill during draw, remember original.
                                                if ( cfg.fillAfter ) {
                                                        var fill = window.getComputedStyle( shape ).fill;
                                                        if ( fill && fill !== 'none' && fill !== 'rgba(0, 0, 0, 0)' ) {
                                                                fills.push( shape );
                                                                gsap.set( shape, { fillOpacity: 0 } );
                                                        }
                                                }
                                        } );

                                        tl.to( shapes, {
                                                strokeDashoffset: 0,
                                                duration: duration,
                                                ease: ease,
                                                stagger: stagger,
                                        } );

                                        if ( cfg.fillAfter && fills.length ) {
                                                tl.to( fills, {
                                                        fillOpacity: 1,
                                                        duration: Math.max( 0.4, duration * 0.4 ),
                                                        ease: 'power1.out',
                                                }, '-=' + ( duration * 0.25 ) );
                                        }
                                } else if ( anim === 'fade-scale' ) {
                                        gsap.set( svg, { transformOrigin: '50% 50%' } );
                                        gsap.set( shapes, { opacity: 0, scale: 0.6, transformOrigin: '50% 50%' } );
                                        tl.to( shapes, {
                                                opacity: 1,
                                                scale: 1,
                                                duration: duration,
                                                ease: ease,
                                                stagger: stagger,
                                        } );
                                } else if ( anim === 'fade-paths' ) {
                                        gsap.set( shapes, { opacity: 0 } );
                                        tl.to( shapes, {
                                                opacity: 1,
                                                duration: duration,
                                                ease: ease,
                                                stagger: stagger,
                                        } );
                                } else if ( anim === 'rotate-in' ) {
                                        gsap.set( svg, { transformOrigin: '50% 50%' } );
                                        gsap.set( shapes, { opacity: 0, rotation: -45, scale: 0.7, transformOrigin: '50% 50%' } );
                                        tl.to( shapes, {
                                                opacity: 1,
                                                rotation: 0,
                                                scale: 1,
                                                duration: duration,
                                                ease: ease,
                                                stagger: stagger,
                                        } );
                                }

                                return tl;
                        };

                        var timeline = build();

                        if ( onScroll && GSAPEW.hasScrollTrigger() && ! loop ) {
                                window.ScrollTrigger.create(
                                        GSAPEW.scrollTriggerConfig( el, repeat, { animation: timeline } )
                                );
                        } else if ( onScroll && GSAPEW.hasScrollTrigger() && loop ) {
                                window.ScrollTrigger.create( {
                                        trigger: el,
                                        start: 'top 85%',
                                        onEnter: function () {
                                                timeline.play();
                                        },
                                } );
                        } else {
                                timeline.play();
                        }
                },

                /**
                 * Compute the drawable length of an SVG shape. Falls back to a
                 * perimeter estimate for shapes without getTotalLength support.
                 *
                 * @param {SVGElement} shape Target shape.
                 * @return {number} Length in user units.
                 */
                _pathLength: function ( shape ) {
                        if ( typeof shape.getTotalLength === 'function' ) {
                                try {
                                        var l = shape.getTotalLength();
                                        if ( l && isFinite( l ) ) {
                                                return l;
                                        }
                                } catch ( e ) {
                                        // Ignore and fall through to estimate.
                                }
                        }
                        var tag = shape.tagName.toLowerCase();
                        if ( tag === 'rect' ) {
                                var w = parseFloat( shape.getAttribute( 'width' ) ) || 0;
                                var h = parseFloat( shape.getAttribute( 'height' ) ) || 0;
                                return ( w + h ) * 2;
                        }
                        if ( tag === 'circle' ) {
                                var r = parseFloat( shape.getAttribute( 'r' ) ) || 0;
                                return 2 * Math.PI * r;
                        }
                        if ( tag === 'ellipse' ) {
                                var rx = parseFloat( shape.getAttribute( 'rx' ) ) || 0;
                                var ry = parseFloat( shape.getAttribute( 'ry' ) ) || 0;
                                return Math.PI * ( 3 * ( rx + ry ) - Math.sqrt( ( 3 * rx + ry ) * ( rx + 3 * ry ) ) );
                        }
                        return 1000;
                },

                /* =============================================================
                 * 10. Hero to Bento
                 * =========================================================== */
                initHeroBento: function ( el, cfg ) {
                        var gsap = window.gsap;
                        var stage = el.querySelector( '.gsap-ew-herobento-stage' );
                        var grid = el.querySelector( '.gsap-ew-herobento-grid' );
                        var hero = el.querySelector( '.gsap-ew-herobento-hero' );
                        var content = el.querySelector( '.gsap-ew-herobento-content' );
                        var items = el.querySelectorAll( '.gsap-ew-herobento-item' );
                        if ( ! stage || ! grid || ! hero ) {
                                return;
                        }

                        var ease = cfg.easing || 'power2.out';
                        var scrollLen = typeof cfg.scrollLength === 'number' ? cfg.scrollLength : 160;

                        // Defensive cleanup: kill any ScrollTrigger already bound to this
                        // stage so re-initialisation can never stack a second pin.
                        if ( GSAPEW.hasScrollTrigger() ) {
                                window.ScrollTrigger.getAll().forEach( function ( st ) {
                                        if ( st.trigger === stage ) {
                                                st.kill( true );
                                        }
                                } );
                        }

                        // Respect reduced-motion and missing ScrollTrigger: show the
                        // final grid layout without any scroll effect.
                        var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
                        var editMode = window.elementorFrontend && typeof window.elementorFrontend.isEditMode === 'function' && window.elementorFrontend.isEditMode();

                        if ( reduceMotion || editMode || ! GSAPEW.hasScrollTrigger() ) {
                                gsap.set( hero, { clearProps: 'transform' } );
                                if ( content ) {
                                        gsap.set( content, { clearProps: 'transform' } );
                                }
                                gsap.set( items, { opacity: 1, scale: 1, y: 0 } );
                                return;
                        }

                        // Compute the transform that makes the hero cover the whole
                        // viewport (its "full screen" starting state). Uses offset
                        // geometry so it is independent of scroll position.
                        var compute = function () {
                                var gridW = grid.offsetWidth;
                                var gridH = grid.offsetHeight;
                                var heroW = hero.offsetWidth;
                                var heroH = hero.offsetHeight;
                                if ( ! heroW || ! heroH ) {
                                        return { x: 0, y: 0, scale: 1 };
                                }
                                var vw = window.innerWidth;
                                var vh = window.innerHeight;
                                // Scale so the hero fully covers the viewport.
                                var scale = Math.max( vw / heroW, vh / heroH );
                                // Translate the hero centre to the grid centre (which,
                                // while the stage is pinned, equals the viewport centre).
                                var heroCenterX = hero.offsetLeft + ( heroW / 2 );
                                var heroCenterY = hero.offsetTop + ( heroH / 2 );
                                var dx = ( gridW / 2 ) - heroCenterX;
                                var dy = ( gridH / 2 ) - heroCenterY;
                                return { x: dx, y: dy, scale: scale };
                        };

                        gsap.set( hero, { transformOrigin: 'center center', zIndex: 5, willChange: 'transform' } );
                        if ( content ) {
                                gsap.set( content, { transformOrigin: 'center center' } );
                        }

                        var tl = gsap.timeline( {
                                scrollTrigger: {
                                        trigger: stage,
                                        start: 'top top',
                                        end: '+=' + ( window.innerHeight * ( scrollLen / 100 ) ),
                                        scrub: true,
                                        pin: true,
                                        anticipatePin: 1,
                                        invalidateOnRefresh: true,
                                },
                        } );

                        // fromTo with function-based values so they recompute on refresh
                        // (resize / responsive), keeping the effect accurate.
                        tl.fromTo(
                                hero,
                                {
                                        x: function () { return compute().x; },
                                        y: function () { return compute().y; },
                                        scale: function () { return compute().scale; },
                                },
                                { x: 0, y: 0, scale: 1, ease: 'none' },
                                0
                        );

                        // Counter-scale the hero content by the inverse of the hero's
                        // scale so the heading / button stay a readable, roughly constant
                        // size (rather than ballooning to 2-3x while the hero is full
                        // screen) and remain centred instead of being clipped off-edge.
                        if ( content ) {
                                tl.fromTo(
                                        content,
                                        {
                                                scale: function () {
                                                        var s = compute().scale;
                                                        return s ? ( 1 / s ) : 1;
                                                },
                                        },
                                        { scale: 1, ease: 'none' },
                                        0
                                );
                        }

                        if ( items.length ) {
                                tl.fromTo(
                                        items,
                                        { opacity: 0, scale: 0.85, y: 30 },
                                        { opacity: 1, scale: 1, y: 0, ease: ease, stagger: 0.06 },
                                        0.35
                                );
                        }
                },
        };

        // Expose the namespace for debugging / extension.
        window.GSAPElementorWidgets = GSAPEW;

        /**
         * Standard front-end boot.
         */
        if ( document.readyState === 'loading' ) {
                document.addEventListener( 'DOMContentLoaded', function () {
                        GSAPEW.initAll( document );
                } );
        } else {
                GSAPEW.initAll( document );
        }

        /**
         * Elementor editor / preview integration. When a widget is added or edited
         * in the Elementor editor, re-run initialisation for that element.
         */
        if ( window.jQuery ) {
                window.jQuery( window ).on( 'elementor/frontend/init', function () {
                        if ( typeof window.elementorFrontend === 'undefined' ) {
                                return;
                        }

                        var types = [
                                'gsap-animated-heading',
                                'gsap-scroll-counter',
                                'gsap-parallax-section',
                                'gsap-staggered-grid',
                                'gsap-timeline-reveal',
                                'gsap-animated-text',
                                'gsap-icon-box-3d',
                                'gsap-reveal-on-scroll',
                                'gsap-svg-animator',
                                'gsap-hero-bento',
                        ];

                        types.forEach( function ( type ) {
                                window.elementorFrontend.hooks.addAction(
                                        'frontend/element_ready/' + type + '.default',
                                        function ( $scope ) {
                                                var scopeEl = $scope && $scope[ 0 ] ? $scope[ 0 ] : document;
                                                var isEdit = window.elementorFrontend &&
                                                        typeof window.elementorFrontend.isEditMode === 'function' &&
                                                        window.elementorFrontend.isEditMode();
                                                // Only reset the init flag inside the Elementor editor so re-edits
                                                // re-animate. On the live front end the DOMContentLoaded pass has
                                                // already initialised each widget, and resetting here would create
                                                // duplicate tweens / ScrollTriggers (e.g. double-pinning the Hero
                                                // to Bento widget), so we leave the flags alone and initAll() will
                                                // simply skip any node already marked as initialised.
                                                if ( isEdit ) {
                                                        var nodes = scopeEl.querySelectorAll( '[data-gsap-type]' );
                                                        nodes.forEach( function ( n ) {
                                                                n.removeAttribute( 'data-gsap-init' );
                                                                n.removeAttribute( 'data-gsap-split' );
                                                        } );
                                                }
                                                GSAPEW.initAll( scopeEl );
                                                if ( typeof window.ScrollTrigger !== 'undefined' ) {
                                                        window.ScrollTrigger.refresh();
                                                }
                                        }
                                );
                        } );
                } );
        }
} )();
