"use strict";
    
$(document).ready(function () {

    document.addEventListener("deviceready", function () {
        FastClick.attach(document.body);
    }, false);

    // Show/hide menu toggle
    $('.item-content').click(function () {
        var href = $(this).attr('data-href');
        if (href === "#dashboard/1") {
            renderDash('1');
        } else if (href === "#dashboard/2") {
            renderDash('2');
        } else if (href === "#dashboard/3") {
            renderDash('3');
        }
    });
    
    var uri = new URI(window.location.href);
    // Get query string
    var query = URI.parseQuery(uri.query());
    console.log(uri + " : " + JSON.stringify(query));
    
    if(query['dash'] != null) {
        renderDash(query.dash, query['sch']); 
    } else {
        //dashboard1.render();
        renderDash('1', 0);
    }

}());

function renderDash(dash, sched) {
    if (dash === "1") {
        $('#vis-menu').remove();
        dashboard1.render();
    } else if (dash === "2") {
        $('#vis-menu').remove();
        dashboard2.render();
    } else if (dash === "3") {
        dashboard3.render(sched);
    }
}

var visDays = ["U", "M", "T", "W", "H", "F", "S"];

window.onresize = function (event) {
    var navbar_height = $('#custom_navbar').height() || 0;
    $("#left-nav").css("top", navbar_height + "px");
    $("#content").css("top", navbar_height + "px");
};
