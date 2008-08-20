
	function getDashboardConfig() {
		var rData = new Array();
		$('.tx-communityflexiblelayout-dashboard-container').each(function() {
			container = $(this).attr('id').replace('tx-communityflexiblelayout-dashboard-col', '');
			counter = 1;
			$(this).find('.widget').each(function() {
				var widget = [
					container,
					counter++,
					$(this).attr('id').replace('tx-communityflexiblelayout-dashboard-widget-', '')
				];
				rData.push(widget); 
			});
		});
		return rData;
	}

	$(document).ready(function() {
		if (__EDIT__) {
			$('#tx-communityflexiblelayout-clipboard .widget').each(function() {
				$(this).find('.content').hide();
			});
			$('.tx-communityflexiblelayout-dashboard-container, #tx-communityflexiblelayout-clipboard').sortable({
				'connectWith': ['.tx-communityflexiblelayout-dashboard-container', '#tx-communityflexiblelayout-clipboard'],
				'cancel': '.undraggable',
				'cursor': 'crosshair',
				'over': function(e, ui) {
					if (ui.element.attr('id') == 'tx-communityflexiblelayout-clipboard') {
						ui.helper.find('.label').show();
						ui.helper.find('.content').hide();
					} else {
						ui.helper.find('.content').show();
					}
				},
				'update': function(e, ui) {
					$.get('/index.php', {
						'id': __PAGEID__,
						'cmd': 'saveDashboard',
						'dashboardConfig[]': getDashboardConfig()
					});
				}
			});
		}
	});
