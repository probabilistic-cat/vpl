function styleProductInfoBottom() {
    let span = $('#spanProductInfoBottoms');

    if (typeof span.attr('id') === 'undefined') {
        return;
    }

    let table = span.parent().parent().find('table');

    let firstTh = table.find('thead th').first();
    firstTh.css('width', '3%');
    firstTh.next().css('width', '28%');
    firstTh.next().next().css('width', '70%');
    firstTh.next().next().next().css('width', '0');

    table.find('tbody tr').each(function(){
        $(this).find('td:eq(2) textarea').css('height', '150px');
    });
}

$(document).ready( () => {
    styleProductInfoBottom();
});
