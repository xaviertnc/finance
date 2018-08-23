/* globals window, F1, Happy2, HappyInput, HappyField */
/* eslint-env es6 */

window.F1 = window.F1 || { afterPageLoadScripts: [] };

F1.clients = {};

F1.afterPageLoadScripts.push(function initClients()
{
  F1.console.log('This is AFTER Clients loaded succesfully!');
});
