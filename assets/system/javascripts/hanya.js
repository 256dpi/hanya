/*
 * Unobstrusive Functions
 *
 * .hanya-editor-html -> turn textarea into an editor
 * .hanya-has-command -> link executes data-command
 * .hanya-editable -> container shows edit HanyaWindow on click
 * .hanya-createable -> link opens HanyaManager with a new definition
 * .hanya-gain-focus -> get focus after loading in a window
 */

/*
 * Main Hanya Class for Unobstrusive JS Access
 */
var Hanya = {
	
	// Initialize Javascript for Hanya Site
	init: function() {
		
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
  			HanyaWindow.createFromURL(window.location+"?command=definition_manager",data,"800px");
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
      			  window.location.reload();
      			}
      		});
        }).appendTo(toolbar);
        
        // Order Down
        $("<li>").append($("<span>")).addClass("hanya-definition-toolbar-orderdown").click(function(){
    			$.post(window.location.href+"?command=definition_orderdown",data,function(ret) {
      			if(ret == "ok") {
      			  window.location.reload();
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
			data = {
				definition: $(this).data("definition"),
				argument: $(this).data("argument")
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
		HanyaWindow.createFromURL(window.location+"?command=admin_form",{},"300px");
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
		
		// Add Enhancements
		this.enhancements();
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
		if($(".hanya-editor-html").size() > 0) {
		  $(".hanya-editor-html").cleditor({
  			width: "99%",
  			heigth: "300px",
  			controls: "bold italic underline subscript superscript style removeformat bullets numbering | undo redo | rule image link unlink | cut copy paste pastetext source",
  		});
  		$(".hanya-row-html br").remove();
		}
		
		// Set Focus
		if($(".hanya-gain-focus").size() > 0) {
		  $(".hanya-gain-focus")[0].focus();
		}
	}
}

// Start the Hanya Processing
$(document).ready(function(){
	Hanya.init();
});