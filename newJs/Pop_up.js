( function( window ) {

'use strict';

// class helper functions from bonzo https://github.com/ded/bonzo

function classReg( className ) {
  return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
}

// classList support for class management
// altho to be fair, the api sucks because it won't accept multiple classes at once
var hasClass, addClass, removeClass;

if ( 'classList' in document.documentElement ) {
  hasClass = function( elem, c ) {
    return elem.classList.contains( c );
  };
  addClass = function( elem, c ) {
    elem.classList.add( c );
  };
  removeClass = function( elem, c ) {
    elem.classList.remove( c );
  };
}
else {
  hasClass = function( elem, c ) {
    return classReg( c ).test( elem.className );
  };
  addClass = function( elem, c ) {
    if ( !hasClass( elem, c ) ) {
      elem.className = elem.className + ' ' + c;
    }
  };
  removeClass = function( elem, c ) {
    elem.className = elem.className.replace( classReg( c ), ' ' );
  };
}

function toggleClass( elem, c ) {
  var fn = hasClass( elem, c ) ? removeClass : addClass;
  fn( elem, c );
}

var classie = {
  // full names
  hasClass: hasClass,
  addClass: addClass,
  removeClass: removeClass,
  toggleClass: toggleClass,
  // short names
  has: hasClass,
  add: addClass,
  remove: removeClass,
  toggle: toggleClass
};

// transport
if ( typeof define === 'function' && define.amd ) {
  // AMD
  define( classie );
} else {
  // browser global
  window.classie = classie;
}

})( window );

var ModalEffects = (function() {

  function init() {

    var overlay = document.querySelector( '.md-overlay' );

    [].slice.call( document.querySelectorAll( '#md-trigger' ) ).forEach( function( el, i ) {

      var modal = document.querySelector( '#' + el.getAttribute( 'data-modal' ) ),
        close = modal.querySelector( '.md-close' );

      function removeModal( hasPerspective ) {
        classie.remove( modal, 'md-show' );

        if( hasPerspective ) {
          classie.remove( document.documentElement, 'md-perspective' );
          $("#overlay").removeClass('blur-in');
        }
      }

      function removeModalHandler() {
        removeModal( classie.has( el, 'md-setperspective' ) );
        $("#overlay").removeClass('blur-in');
      }

      el.addEventListener( 'click', function( ev ) {
        classie.add( modal, 'md-show' );
        $("#overlay").addClass('blur-in');
        overlay.removeEventListener( 'click', removeModalHandler );
        overlay.addEventListener( 'click', removeModalHandler );

        if( classie.has( el, 'md-setperspective' ) ) {
          setTimeout( function() {
            classie.add( document.documentElement, 'md-perspective' );
          }, 25 );
        }
      });

      close.addEventListener( 'click', function( ev ) {
        ev.stopPropagation();
        removeModalHandler();
        $("#overlay").removeClass('blur-in');
      });

    } );

  }

  init();

})();



//type为2时,有两个判断按钮
function confirm_z(type,content){
	$(".md-modal").addClass("md-show");
	$(".cover").addClass("blur-in");
	$(".Pop_up_title").html(content);
	if(type == 0){
		$(".md-jugde").hide();
		$(".md-close").hide();
	}
	if(type == 1){
		$(".md-jugde").hide();
		$(".md-close").show();
	}
	if(type == 2){
		$(".md-jugde").show();
		$(".md-close").hide();
	}
}