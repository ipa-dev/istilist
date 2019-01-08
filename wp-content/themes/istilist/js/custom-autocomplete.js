jQuery( document ).ready( function() {
    var baseUrl = window.location.origin;

    jQuery( '#school_event' ).autocomplete( baseUrl + '/autocomplete-school', {
        selectFirst: true
    });

    jQuery( '#designer' ).autocomplete( baseUrl + '/autocomplete-designer', {
        selectFirst: true
    });
});
