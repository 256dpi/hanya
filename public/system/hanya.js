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
		if(top < 25) { top = 25; }
		element.css("top",top).css("left",left);
	},
	deleteEntry: function() {
		var def = $("#hanya-input-definition").first().val();
		var id = $("#hanya-input-id").first().val();
		$.post(window.location.href+"?command=manager_delete",{"definition":def,"id":id},function(data) {
			if(data == "ok") {
				window.location.reload();
			}
		});
	}
}

$(document).ready(function(){
	Hanya.init();
});