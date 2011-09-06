var Hanya = {
	init: function() {
		$(".hanya-editable").click(function(){
			Overlay.create(Manager.remove);
			Manager.loadContent(window.location+"?command=manager_form",{"definition":$(this).data("definition"),"id":$(this).data("id")},"800px");
		});
		$(".hanya-createable").click(function(){
			Overlay.create(Manager.remove);
			Manager.loadContent(window.location+"?command=manager_form",{"definition":$(this).data("definition"),"id":$(this).data("id")},"800px");
		});
	},
	deleteEntry: function() {
		var def = $("#hanya-input-definition").first().val();
		var id = $("#hanya-input-id").first().val();
		$.post(window.location.href+"?command=manager_delete",{"definition":def,"id":id},function(data) {
			if(data == "ok") {
				window.location.reload();
			}
		});
	},
	login: function() {
		Overlay.create(Manager.remove);
		Manager.loadContent(window.location+"?command=admin_form",{},"300px");
	},
	command: function(command) {
		window.location = window.location+"?command="+command;
	}
}

var Overlay = {
	create: function(fn) {
		var overlay = $("<div>").addClass("hanya-overlay");
		overlay.click(fn);
		$("body").append(overlay);
	},
	remove: function() {
		$(".hanya-overlay").remove();
	}
}

var Manager = {
	loadContent: function(url,data,width) {
		var manager = $("<div>").addClass("hanya-manager").width(width);
		manager.load(url,data,function(){
			Manager.center(manager);
			Manager.enhancements();
		});
		
		$(window).resize(function(){
			Manager.center(manager);
		});
		
		$("body").append(manager);
	},
	/*withContent: function(content,width) {
		var manager = $("<div>").addClass("hanya-manager").width(width);
		manager.html(content);
		
		$(window).resize(function(){
			Manager.center(manager);
		});
		
		$("body").append(manager);
	},*/
	remove: function() {
		$(".hanya-manager").remove();
		Overlay.remove()
	},
	center: function(element) {
		var left = ($(window).width()-element.width())/2;
		var top = ($(window).height()-element.height())/2;
		if(top < 25) { top = 25; }
		element.css("top",top).css("left",left);
	},
	enhancements: function() {
		$(".hanya-editor-html").cleditor({
			width: "99%",
			heigth: "300px",
			controls: "bold italic underline subscript superscript style removeformat bullets numbering | undo redo | rule image link unlink | cut copy paste pastetext source",
		});
	}
}

$(document).ready(function(){
	Hanya.init();
});