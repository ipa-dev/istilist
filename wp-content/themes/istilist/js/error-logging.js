function logError( details ) {
    jQuery.ajax({
      type: 'POST',
      url: window.location.origin + '/wp-json/istilist/v2/errors/',
      data: JSON.stringify({context: navigator.userAgent, details: details}),
      contentType: 'application/json; charset=utf-8'
    });
  }
window.onerror = function( message, file, line ) {
    logError( file + ':' + line + '\n\n' + message );
};
jQuery( document ).ajaxError( function( e, xhr, settings ) {
    logError( settings.url + ':' + xhr.status + '\n\n' + xhr.responseText );
});
