;(function ( $ ) {
	"use strict";

	$(function () {
        /**
         * Show popup if product is added on products catalog page
         * and cart ajax is enabled      
         */                       
        jQuery('#bestselling-list-more-span').on( 'click', function(){
            console.log( 'test' );
            jQuery('.bestselling-list-item.hidden-item').slideToggle();            
        });

	});

}(jQuery));