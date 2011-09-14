/*
 * Unobstrusive Functions
 *
 * .hanya-editor-html -> turn textarea into an editor
 * .hanya-has-command -> link executes data-command
 * .hanya-editable -> container shows edit HanyaWindow on click
 * .hanya-createable -> container show add HanyaWindow on click
 */

/*
 * Main Hanya Class for Unobstrusive JS Access
 */
var Hanya = {
	
	// Initialize Javascript for Hanya Site
	init: function() {
		
		// Interactify Editables
		$(".hanya-editable").click(function(){
			data = {
				definition: $(this).data("definition"),
				id: $(this).data("id"),
			}
			HanyaWindow.createFromURL(window.location+"?command=definition_manager",data,"800px");
		});
		
		// Interactify Createables
		$(".hanya-createable").click(function(){
			data = {
				definition: $(this).data("definition"),
				id: $(this).data("id")
			}
			HanyaWindow.createFromURL(window.location+"?command=definition_manager",data,"800px");
		});
		
		// Map Commands
		$(".hanya-has-command").click(function(){
			Hanya.executeCommand($(this).data("command"));
		});
	},
	
	// Execute an Command
	executeCommand: function(command) {
		window.location = window.location+"?command="+command;
	},
	
	// Open Login Hanya	
	login: function() {
		HanyaWindow.createFromURL(HanyaWindow.location+"?command=admin_form",{},"300px");
	},
		
	// Open Update Window
	update: function() {
		HanyaWindow.createWithIFrame(window.location+"?command=update","800px","400px");
	}

}

/*
 * HanyaOverlay HanyaWindow Class
 */
var HanyaOverlay = {
	
	// Create new Overlay
	create: function(fn) {
		var overlay = $("<div>").addClass("hanya-overlay");
		overlay.click(fn);
		$("body").append(overlay);
	},
	
	// Remove all HanyaOverlays
	remove: function() {
		$(".hanya-overlay").remove();
	}

}

/*
 * HanyaWindow Class for Form Rendering
 */
var HanyaWindow = {
	
	// Create HanyaWindow with Remote Content
	createFromURL: function(url,parameters,width) {
		$.ajax({
			type: "POST",
			url: url,
			data: parameters,
			success: function(content) {
				HanyaWindow.create(content,width);
			}
		});
	},
	
	// Create HanyaWindow with Iframge Content
	createWithIFrame: function(url,width,height) {
		var content = $("<iframe>").attr("src",url);
		HanyaWindow.create(content,width,height);
	},
	
	// Create HanyaWindow with Inline Content
	create: function(content,width,height) {
		
		// Create Overlay
		HanyaOverlay.create(this.remove);
		
		// Create Window
		var hwindow = $("<div>").addClass("hanya-manager").width(width);
		hwindow.html(content);
		
		// Check for Height
		if(height) {
			hwindow.height(height);
		}
		
		// Appen to Body
		$("body").append(hwindow);
		
		// Add Resize Handler
		$(window).resize(function(){
			HanyaWindow.center(hwindow);
		});
		
		// Precenter Window
		this.center(hwindow);
	},

	// Remove All HanyaWindows and HanyaOverlays
	remove: function() {
		$(".hanya-manager").remove();
		HanyaOverlay.remove()
	},

	// Center the HanyaWindow
	center: function(element) {
		var left = ($(window).width()-element.width())/2;
		var top = ($(window).height()-element.height())/2;
		if(top < 25) { top = 25; }
		element.css("top",top).css("left",left);
	},
	
	// Add Enhancements to its Content
	enhancements: function() {
		
		// Add Editor to Textareas
		$(".hanya-editor-html").cleditor({
			width: "99%",
			heigth: "300px",
			controls: "bold italic underline subscript superscript style removeformat bullets numbering | undo redo | rule image link unlink | cut copy paste pastetext source",
		});
		$(".hanya-row-html br").remove();
		
	}
}

/*
 * Class with Function for Handling Definitions
 */

var HanyaDefinition = {
	
	// Delete an Entry
	deleteEntry: function() {
		var def = $("#hanya-input-definition").first().val();
		var id = $("#hanya-input-id").first().val();
		$.post(window.location.href+"?command=definition_remove",{"definition":def,"id":id},function(data) {
			if(data == "ok") {
				window.location.reload();
			}
		});
	}
	
}

// Start the Hanya Processing
$(document).ready(function(){
	Hanya.init();
});