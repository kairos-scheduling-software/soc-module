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

    var dash = getParameterByName("dash");
    var id = getParameterByName("id");
    
    console.log("Query : dash=" + dash + ", id=" + id +";");

    if (dash !== null && dash !== undefined) {
        renderDash(dash, id);
    } else {
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
        dashboard3.render(sched, false);
    } else if (dash === "4") {
        dashboard3.render(sched, true);
    }
}

function getParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

var visDays = ["U", "M", "T", "W", "H", "F", "S"];
var dayMap =
    {
        "U": 0,
        "M": 1,
        "T": 2,
        "W": 3,
        "H": 4,
        "F": 5,
        "S": 6
    };

window.onresize = function (event) {
    var navbar_height = $('#custom_navbar').height() || 0;
    $("#left-nav").css("top", navbar_height + "px");
    $("#content").css("top", navbar_height + "px");
};
