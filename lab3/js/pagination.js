$(document).ready(function(total_records, limit, page){
    $('.pagination').pagination({
        items: total_records,
        itemsOnPage: limit,
        cssStyle: 'light-theme',
        currentPage : page,
        hrefTextPrefix : 'list?page='
    });
});