$(function() {
    //$('.widget-content').mCustomScrollbar({theme: 'dark-thin'});

    var $list = $('#js-entries-list');

    intelli.entriesOffset = $list.data('offset');

    $list.on('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            if (false === intelli.entriesOffset) {
                return;
            }
            $.get(window.location.href + 'read.json', {offset: intelli.entriesOffset}, function(response) {
                $list.find('tbody').append(response.html);
                intelli.entriesOffset = response.offset
                    ? intelli.entriesOffset + response.offset
                    : response.offset;
            })
        }
    })

    $('.js-cmd-view-details').on('click', function(e) {
        e.preventDefault();

        var $this = $(this).button('loading');

        $.get(window.location.href + $(this).data('id') + '.json', {}, function(response) {
            $('#js-ph-details').html(response.log);
            $this.button('reset');
        })
    });
});