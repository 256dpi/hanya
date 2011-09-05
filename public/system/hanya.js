var Hanya = {
	init: function() {
		$(".hanya-editable").click(function(){
			Hanya.createOverlay(Hanya.removeManager);
			Hanya.createManager($(this).data("definition"),$(this).data("id"));
		});
		$(".hanya-createable").click(function(){
			Hanya.createOverlay(Hanya.removeManager);
			Hanya.createManager($(this).data("definition"));
		});
	},
	createOverlay: function(fn) {
		var overlay = $("<div>").addClass("hanya-overlay");
		overlay.click(fn);
		$("body").append(overlay);
	},
	removeOverlay: function() {
		$(".hanya-overlay").remove();
	},
	createManager: function(definition,id) {
		var manager = $("<div>").addClass("hanya-manager").width("500px");
		manager.load(window.location+"?command=manager_form",{"definition":definition,"id":id},function(){
			Hanya.centerManager(manager);
			$(window).resize(function(){
				Hanya.centerManager(manager);
			});
		});
		
		$("body").append(manager);
	},
	removeManager: function() {
		$(".hanya-manager").remove();
		Hanya.removeOverlay();
	},
	centerManager: function(element) {
		var left = ($(window).width()-element.width())/2;
		var top = ($(window).height()-element.height())/2;
		element.css("top",top).css("left",left);
	}
}

$(document).ready(function(){
	Hanya.init();
});