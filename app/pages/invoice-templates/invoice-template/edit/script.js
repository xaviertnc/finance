/* globals window, F1, Happy2, HappyInput, HappyField */
/* eslint-env es6 */

window.F1 = window.F1 || { afterPageLoadScripts: [] };

F1.clients = {};

F1.afterPageLoadScripts.push(function initClients()
{
  F1.console.log('This is AFTER Clients loaded succesfully!');
	Calendar.setup({
		inputField     :    'start-date',  // id of the input field
		ifFormat       :    '%Y-%m-%d',    // format of the input field
		button         :    'btnCal1',     // trigger for the calendar (button ID)
		align          :    'Bl',          // alignment (defaults to "Bl")
		singleClick    :    true
	});
	Calendar.setup({
		inputField     :    'expire-date', // id of the input field
		ifFormat       :    '%Y-%m-%d',    // format of the input field
		button         :    'btnCal2',     // trigger for the calendar (button ID)
		align          :    'Bl',          // alignment (defaults to "Bl")
		singleClick    :    true
	});  
});
