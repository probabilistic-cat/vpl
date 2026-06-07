function styleInfoBottom(spanId) {
    let span = $('#' + spanId);

    if (typeof span.attr('id') === 'undefined') {
        return;
    }

    let table = span.parent().parent().find('table');

    let firstTh = table.find('thead th').first();
    firstTh.css('width', '3%');
    firstTh.next().css('width', '27%');
    firstTh.next().next().css('width', '70%');
    firstTh.next().next().next().css('width', '0');

    table.find('tbody tr').each(function(){
        $(this).find('td:eq(2) textarea').css('height', '150px');
    });
}

$(document).ready( () => {
    let urlShort = window.location.pathname;

    styleInfoBottom('spanProductInfoBottoms');
    styleInfoBottom('spanStyleInfoBottoms');

    if ((~urlShort.indexOf('admin/app/style') || ~urlShort.indexOf('admin/app/product')) && ~urlShort.indexOf('edit')) {
        $('a.btn.btn-success.btn-sm.sonata-ba-action').click(function(){
            for (var i = 1; i <= 5; i++) {
                setTimeout(function() {
                    styleInfoBottom('spanProductInfoBottoms');
                    styleInfoBottom('spanStyleInfoBottoms');
                }, i * 1000);
            }
        });
    }
});
