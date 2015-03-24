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

var visDays = ["U", "M", "T", "W", "H", "F", "S"];

function visGetSchedData(sched_name) {
    /*
    $.ajax({
        dataType: "json",
        url: 'vis/8/0',
        success: function (data) {
            var newDays;
            $.each(data, function (i, d) {
                newDays = [];
                var pd = jQuery.parseJSON(d.days);
                for (var j = 0; j < pd.length; j++) {
                    newDays[j] = visDays[pd[j]];
                }
                data[i].days = newDays;
            });
        }
    });
    */
    console.log(sched_name);
    if (sched_name == "None") {
        return [];
    }

    return vis_data[sched_name];
}
