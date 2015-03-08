(function () {
    "use strict";

    document.addEventListener("deviceready", function () {
        FastClick.attach(document.body);
    }, false);

    // Show/hide menu toggle
    $('.item-content').click(function () {
        var href = $(this).attr('data-href');
        if (href === "#dashboard/1") {
            dashboard1.render();
        } else if (href === "#dashboard/2") {
            dashboard2.render();
        } else if (href === "#dashboard/3") {
            dashboard3.render();
        }
    });

    dashboard1.render();

}());
