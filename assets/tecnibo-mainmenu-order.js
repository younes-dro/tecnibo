/*
 * 
 */
;
jQuery(document).ready(function(jQuery) {
    
    	/* Submit button click event */
	jQuery("#custom-loading").hide();
	jQuery("#order-submit").click(function(e) {
            e.preventDefault();
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
	var newOrder = jQuery("ul.custom-order-mainmenu").sortable("toArray");
	jQuery("#custom-loading").show();
	jQuery("#hidden-custom-order").val(newOrder);

	return true;
}
