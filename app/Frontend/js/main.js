$('.table thead th button').popover({
    placement: 'bottom',
    trigger: 'click',
    title: 'Сортировать',
    content: function() {
        var $list_group = $('<div/>'),
            $list_item = $('<a/>');

        $($list_group).attr({
            'class': 'list-group'
        });

        $($list_item).attr({
            'class': 'list-group-item',
            href: '?sort=' + $(this).attr('data-key')
        });

        var $sort_asc_item = $list_item.clone(),
            $sort_desc_item = $list_item.clone(),
            $sort_reset_item = $list_item.clone();

        $($sort_asc_item).addClass('sort-asc').attr({
            href: $($sort_asc_item).attr('href') + '&order=asc'
        }).text('По возрастанию');

        $($sort_desc_item).addClass('sort-desc').attr({
            href: $($sort_desc_item).attr('href') + '&order=desc'
        }).text('По убыванию');

        $($sort_reset_item).addClass('sort-desc').attr({
            href: location.pathname
        }).text('Сброс');

        $($list_group).append([$sort_asc_item, $sort_desc_item, $sort_reset_item]);

        return $($list_group)[0].outerHTML;
    }
});

$('.table-recalls tbody tr').bind('click', function() {
    var id = $(this).attr('data-id');
    $(location).attr('href', '/view_recall/' + id);
});

$('#btn-like').bind('click', function() {
    var self = this;
    $.ajax({
        url: '/like_recall',
        method: 'POST',
        data: 'id=' + $(this).attr('data-id'),
        beforeSend: function() {
            $(self).attr('disabled',  true);
        }
    }).done(function(res) {
        $(self).removeAttr('disabled');
        if(res.success) {
            var counter = parseInt($('#recall_likes_counter').text());

            switch(res.action) {
                case 'like':
                    counter++;
                    break;
                case 'dislike':
                    counter--;
                    break;
            }

            $('#recall_likes_counter').text(counter);
        }
    }).fail(function() {
        $(self).removeAttr('disabled');
    });
});