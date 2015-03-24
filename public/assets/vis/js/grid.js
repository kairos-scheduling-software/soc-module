(function () {
    "use strict";

    document.addEventListener("deviceready", function () {
        FastClick.attach(document.body);
    }, false);

    // Show/hide menu toggle
    $('.item-content').click(function () {
        var href = $(this).attr('data-href');
        if (href === "#dashboard/1") {
            $('#vis-menu').remove();
            dashboard1.render();
        } else if (href === "#dashboard/2") {
            $('#vis-menu').remove();
            dashboard2.render();
        } else if (href === "#dashboard/3") {
            dashboard3.render();
        }
    });

    dashboard1.render();

}());
