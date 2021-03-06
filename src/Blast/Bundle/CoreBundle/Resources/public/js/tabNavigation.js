$(document).ready(function() {
    $(document).on('click', 'a[data-gototab]', function() {
        var tabName = $(this).attr('data-gototab');
        $('li[data-tab-name="' + tabName + '"] a').trigger('click');
    });

    var countCollectionsInTabs = function() {
        var countableTabs = $('.countable-tab');

        countableTabs.each(function(index) {
            var tab = $(this);
            tab.data('currentCount', 0);

            var countableClasses = $.grep(tab.attr("class").split(' '), function(v) {
                return v.match(/count-.*/);
            });

            $.each(countableClasses, function(i, cls) {
                var collectionAsForm, collectionAsTable = 0;
                var fieldName = cls.replace('count-', '');

                if (Admin.currentAdmin) {
                    var fieldId = 'field_widget_' + Admin.currentAdmin.uniqid + '_' + fieldName;

                    collectionAsForm = $('#' + fieldId + ' > div.sonata-ba-tabs > div');
                    collectionAsTable = $('#' + fieldId + ' > table > tbody > tr');
                } else {
                    var field = $('*[data-field-name="' + fieldName + '"]');

                    collectionAsForm = field.find('> div.box');
                    collectionAsTable = '';
                }

                tab.data('currentCount', parseInt(tab.data('currentCount'), 10) + (
                    parseInt(collectionAsForm.length, 10) +
                    parseInt(collectionAsTable.length, 10)
                ));
            });

            var counterItem = tab.find('a > span.counter');

            if (counterItem.length == 0) {
                counterItem = tab.find('a').append(
                    $('<span/>').attr({
                        'class': 'counter'
                    })
                ).find('.counter');
            }

            counterItem.html(tab.data('currentCount')).attr('data-count', tab.data('currentCount'));
        });
    };

    countCollectionsInTabs();

    $(document).on('sonata.add_element', function() {
        countCollectionsInTabs();
    });

    var cookieLastTab = getCookie('selectedTab');

    if (cookieLastTab) {
        $('.nav-tabs li[data-tab-name="'+cookieLastTab+'"] a').trigger('click');
    }

    $(document).on('click', '.nav-tabs li[data-tab-name] a', function() {
        document.cookie = 'selectedTab=' + $(this).closest('li').data('tabName');
    });
});

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return null;
}
