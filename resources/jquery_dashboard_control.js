(function($){
	var updateUpDown = function(sortable){
		$('dl:not(.ui-sortable-helper)', sortable)
			.removeClass('first').removeClass('last')
			.find('.up, .down').removeClass('disabled').end()
			.filter(':first').addClass('first').find('.up').addClass('disabled').end().end()
			.filter(':last').addClass('last').find('.down').addClass('disabled').end().end();
	};
	
	var moveUpDown = function(){
		var link = $(this),
			dl = link.parents('dl'),
			prev = dl.prev('dl'),
			next = dl.next('dl');
	
		if(link.is('.up') && prev.length > 0)
			dl.insertBefore(prev);
	
		if(link.is('.down') && next.length > 0)
			dl.insertAfter(next);
	
		updateUpDown(dl.parent());
	};
	
	var addItem = function(){
		var sortable = $(this).parents('.ui-sortable');
		var options = '<span class="options"><a class="up">up</a><a class="down">down</a></span>';
		var tpl = '<dl class="sort"><dt>{name}' + options + '</dt><dd>{desc}</dd></dl>';
		var html = tpl.replace(/{name}/g, 'Dynamic name :D').replace(/{desc}/g, 'Description');
	
		sortable.append(html).sortable('refresh').find('a.up, a.down').bind('click', moveUpDown);
		updateUpDown(sortable);
	};
	
	var emptyTrashCan = function(item){
		item.remove();
	};
	
	var sortableChange = function(e, ui){
		if(ui.sender){
			var w = ui.element.width();
			ui.placeholder.width(w);
			ui.helper.css("width",ui.element.children().width());
		}
	};
	
	var sortableUpdate = function(e, ui){
		if(ui.element[0].id == 'trashcan'){
			emptyTrashCan(ui.item);
		} else {
			updateUpDown(ui.element[0]);
			if(ui.sender)
				updateUpDown(ui.sender[0]);
		}
	};
})(jQuery);


	$(document).ready(function() {
		$('#tx-communityflexiblelayout-clipboard, #tx-communityflexiblelayout-dashboard-col1, #tx-communityflexiblelayout-dashboard-col2, #tx-communityflexiblelayout-dashboard-col3').droppable({
			accept: ".widget",
			tolerance: 'pointer',
			activeClass: 'droppable-active',
			hoverClass: 'droppable-hover',
			drop: function(ev, ui) {
				console.log(this);
			}
		});
		$('#tx-communityflexiblelayout-dashboard-col1, #tx-communityflexiblelayout-dashboard-col2, #tx-communityflexiblelayout-dashboard-col3').sortable({
			'connectWith': ['#tx-communityflexiblelayout-dashboard-col1', '#tx-communityflexiblelayout-dashboard-col2', '#tx-communityflexiblelayout-dashboard-col3']
		});
		//$('#tx-communityflexiblelayout-dashboard .widget').draggable();
	});
