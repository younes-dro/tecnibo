/*
 * 
 */
;
jQuery(document).ready(function(jQuery) {
        /* Select parent cat */
        jQuery('#select-category').click(function(){
            //
        });
    	/* Submit button click event */
	
	jQuery("#order-submit").click(function(e) {
            jQuery("#custom-loading").css('display','inline-block');
            tecnibo_ordersubmit();
	});
});

function customtaxorder_addloadevent(){

	/* Make the Terms sortable */
//	jQuery("ul.custom-order-mainmenu").sortable({
//		placeholder: "sortable-placeholder",
//		revert: false,
//		tolerance: "pointer"
//	});

        jQuery('ul.custom-order-mainmenu').sortable({
          connectWith: 'ul.custom-order-mainmenu',
          update: function(event, ui) {
            var changedList = this.id;
            var order = jQuery(this).sortable('toArray');
            var positions = order.join(',');
            jQuery("#hidden-custom-order").val(positions);
            jQuery("#order-submit").attr("disabled", false);
            
            console.log({
              id: changedList,
              positions: positions
            });
          }
        });
};
addLoadEvent(customtaxorder_addloadevent);

/* Get all the term_orders and send it in a submit. */
function tecnibo_ordersubmit() {

	/* Terms */

	return true;
}
