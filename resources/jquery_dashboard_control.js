
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
	
	function getParam(paramName) {
		var SELF_LOCATION = self.location.href;
		var urlSplit = SELF_LOCATION.split('?');
		if (!urlSplit[1]) { // no query
			return '';
		}
		
		var urlQuery = urlSplit[1];
		var paramsSplit = urlSplit[1].split('&');
		for (var i = 0; i < paramsSplit.length; i++) {
			paramSplit = paramsSplit[i].split('=');
			if (paramSplit[0] == paramName) {
				return paramSplit[1] ? paramSplit[1] : '';
			}
		}
		return '';
   	}
	
	$(document).ready(function() {
		if ( (typeof __EDIT__ != 'undefined') && __EDIT__) {
			$('#tx-communityflexiblelayout-clipboard').slideUp();
			$('#tx-communityflexiblelayout-clipboard-helper').toggle(
				function() {
					$('#tx-communityflexiblelayout-clipboard').slideDown('slow');
					$('#tx-communityflexiblelayout-clipboard-wrap').removeClass('clipboard-box').addClass('clipboard-box-on');
				},
				function() {
					$('#tx-communityflexiblelayout-clipboard').slideUp('slow');
					$('#tx-communityflexiblelayout-clipboard-wrap').removeClass('clipboard-box-on').addClass('clipboard-box');
				}
			);
			$('#tx-communityflexiblelayout-clipboard .widget').each(function() {
				$(this).find('.content').hide();
			});
			$('.tx-communityflexiblelayout-dashboard-container, #tx-communityflexiblelayout-clipboard').sortable({
				'connectWith': ['.tx-communityflexiblelayout-dashboard-container', '#tx-communityflexiblelayout-clipboard'],
				'handle': '.label',
				'cancel': '.undraggable',
				'cursor': 'crosshair',
				'placeholder': 'widget-placeholder',
				'over': function(e, ui) {
					if (ui.element.attr('id') == 'tx-communityflexiblelayout-clipboard') {
						ui.helper.find('.label').show();
						ui.helper.find('.content').hide();
					} else {
						ui.helper.find('.content').show();
						ui.element.addClass('ui-sortable-hover');
						ui.item
							.width(ui.element.width());
					}
				},
				'out': function(e, ui) {
					ui.element.removeClass('ui-sortable-hover');
				},
				'start': function(e, ui) {
					ui.placeholder
						.width("auto")
						.height(ui.helper.height()); 
				},
				'stop': function(e, ui) {
					ui.placeholder
						.width("auto")
						.height("auto"); 
				},
				'sort': function(e, ui) {
					ui.placeholder
						.width("auto")
						.height(ui.helper.height()); // maintain size of placeholder when ui.item is repositioned
				},
				'update': function(e, ui) {
					if (getParam('tx_community[group]').length) {
						$.post('/index.php', {
							'id': __PAGEID__,
							'tx_community[group]': getParam('tx_community[group]'), 
							'tx_communityflexiblelayout[cmd]': 'saveDashboard',
							'tx_communityflexiblelayout[dashboardConfig][]': getDashboardConfig()
						});
					} else if (getParam('tx_community[user]').length) {
						$.post('/index.php', {
							'id': __PAGEID__,
							'tx_community[user]': getParam('tx_community[user]'), 
							'tx_communityflexiblelayout[cmd]': 'saveDashboard',
							'tx_communityflexiblelayout[dashboardConfig][]': getDashboardConfig()
						});
					} else {
						$.post('/index.php', {
							'id': __PAGEID__,
							'tx_communityflexiblelayout[cmd]': 'saveDashboard',
							'tx_communityflexiblelayout[dashboardConfig][]': getDashboardConfig()
						});
					}
				}
			});
		}
	});
