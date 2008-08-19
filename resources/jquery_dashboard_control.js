
	$(document).ready(function() {
		$('#tx-communityflexiblelayout-dashboard-col1 .widget, #tx-communityflexiblelayout-dashboard-col2 .widget, #tx-communityflexiblelayout-dashboard-col3 .widget').each(function() {
			$(this).find('.label').hide();
		});
		$('#tx-communityflexiblelayout-clipboard .widget').each(function() {
			$(this).find('.content').hide();
		});
		$('#tx-communityflexiblelayout-dashboard-col1, #tx-communityflexiblelayout-dashboard-col2, #tx-communityflexiblelayout-dashboard-col3, #tx-communityflexiblelayout-clipboard').sortable({
			'connectWith': ['#tx-communityflexiblelayout-dashboard-col1', '#tx-communityflexiblelayout-dashboard-col2', '#tx-communityflexiblelayout-dashboard-col3', '#tx-communityflexiblelayout-clipboard'],
			'over': function(e, ui) {
				if (ui.element.attr('id') == 'tx-communityflexiblelayout-clipboard') {
					ui.helper.find('.label').show();
					ui.helper.find('.content').hide();
				} else {
					ui.helper.find('.label').hide();
					ui.helper.find('.content').show();
				}
			},
		});
	});
