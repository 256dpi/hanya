/*
 * Unobstrusive Functions
 *
 * .hanya-editor-html -> turn textarea into an editor
 * .hanya-editable -> container shows edit HanyaWindow on click
 * .hanya-createable -> link opens HanyaManager with a new definition
 */

/*
 * Main Hanya Class for Unobstrusive JS Access
 */
var Hanya = {
	
	// Initialize Javascript for Hanya Site
	init: function() {

		// Hanya Javascript Token
		$(".hanya-token-javascript").each(function(){
			$(this).val($(this).val()+"Hanya");
		});
		
		// Interactify Editables
		$(".hanya-editable").mouseenter(function(){
			
			// Store Root
			var editable = $(this);
			
			// Get Data
			var data = {
				definition: editable.data("definition"),
				id: editable.data("id"),
			}		
				
			// Create Toolbar
			var toolbar = $("<ul>").addClass("hanya-definition-toolbar");
			toolbar.css("left",editable.position().left-1).css("top",editable.position().top-25);
				
			// Edit Button
			$("<li>").append($("<span>")).addClass("hanya-definition-toolbar-edit").click(function(){
				$.fancybox.open({href:window.location+"?command=definition_manager&definition="+editable.data("definition")+"&id="+editable.data("id")},{type:"iframe",width:800,height:200,padding:0});
			}).appendTo(toolbar);
				
			// Delete Button
			if(editable.data("is-destroyable")) {
				$("<li>").append($("<span>")).addClass("hanya-definition-toolbar-delete").click(function(){
					if(confirm("OK?")) {
						$.post(window.location.href+"?command=definition_remove",data,function(ret) {
							if(ret == "ok") {
								editable.fadeOut(500);
								toolbar.fadeOut(500,function(){
									editable.remove();
										toolbar.remove();
								});
							}
						});
					}
				}).appendTo(toolbar);
			}
			
			// Ordering Buttons
			if(editable.data("is-orderable")) {
				
					// Order Up
					$("<li>").append($("<span>")).addClass("hanya-definition-toolbar-orderup").click(function(){
					$.post(window.location.href+"?command=definition_orderup",data,function(ret) {
							if(ret == "ok") {
								window.location.reload(true);
							}
						});
					}).appendTo(toolbar);
					
					// Order Down
					$("<li>").append($("<span>")).addClass("hanya-definition-toolbar-orderdown").click(function(){
					$.post(window.location.href+"?command=definition_orderdown",data,function(ret) {
							if(ret == "ok") {
								window.location.reload(true);
							}
						});
					}).appendTo(toolbar);
				}
				
				// Remove Function
				editable.mouseleave(function(){
						toolbar.remove();
				});
				
				// Add Toolbar to Body
				toolbar.appendTo(editable);
				
		});
		
		// Interactify Createables
		$(".hanya-createable").click(function(){
			$.fancybox.open({href:window.location+"?command=definition_manager&definition="+$(this).data("definition")+"&argument="+$(this).data("argument")},{type:"iframe",width:800,height:200,padding:0});
		});
	},
	
	// Open Login Hanya	
	login: function() {
		$.fancybox.open({href:window.location+"?command=admin_form"},{type:"iframe",width:350,height:200,padding:0});
	}
	
}
// Start the Hanya Processing
$(document).ready(function(){
	Hanya.init();
});