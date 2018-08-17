/* globals window, F1, Happy2, HappyInput, HappyField */
/* eslint-env es6 */

window.F1 = window.F1 || { afterPageLoadScripts: [] };

F1.afterPageLoadScripts.push(function initProducts()
{
  F1.console.log('This is AFTER Product EditPage loaded succesfully!');
});
