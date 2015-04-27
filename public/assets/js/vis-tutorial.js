var tour_ended = false;

$(function () {

    $('#center-slide-anchor').css({
        position: 'absolute',
        top: $(window).height() / 2 + 'px',
        left: $(window).width() / 2 + 'px'
    });

    $('#first-slide').css({
        position: 'absolute',
        top: $('#left-nav').offset().top + 'px',
        left: ($('#left-nav').offset().left + $('#left-nav').width()) + 'px',
        height: '220px'
    });

    create_tutorial();

    $('#view-tut-link').click(function (e) {
        e.preventDefault();
        console.log('clicked!');
        if (tour_ended)
            create_tutorial();

        $.tutorialize.cleanRemember('vis-tut');
        $.tutorialize.start('vis-tut');
    });

    setTimeout(function () {
        //$.tutorialize.start('vis-tut');
    }, 5000);

});

function create_tutorial()
{
    var oMode = 'all';
    var oOpacity = 0.6;
    var oPadding = 10;

    $.tutorialize({
        overlayMode: oMode,
        overlayOpacity: oOpacity,
        overlayPadding: oPadding,
        minWidthCSS: '300px',
        minWidth: '300px',
        width: 300,
        fontSize: '15px',
        labelNext: 'Next <i class="fa fa-chevron-right"></i>',
        labelPrevious: '<i class="fa fa-chevron-left"></i> Previous',
        rememberOnceOnly: true,
        remember: true,
        //keyboardNavigation: false,
        slides: [
            {
                title: '<h2>Welcome to the Kairos Data Tools</h2>',
                content: '<p>This page contains a few tools that allow you to see visual representations of your schedule data.</p>Click "Start" to begin your guided tour.</p><p><small style="font-size: 11px">Press the <b style="font-size: 12px">ESC</b> key at any time to quit the tour.</small></p>',
                selector: '#center-slide-anchor',
                overlayMode: 'all',
                position: 'center-center'
            },
            {
                selector: '#first-slide',
                position: 'right-top',
                title: '<h2>Class Counts</h2>',
                content: 'This section will show you the number of classes scheduled per semester/year. The visualization allows you to drill down to see the number and types of classes taught by professor per selected semester/year. This can be done by clicking the data points on the left hand side of the screen.',
                onSlide: function () {
                    //$('.tutorialize-slide-arrow').zIndex($('#left-nav').zIndex() + 1);
                    $('.tutorialize-slide').css( "z-index", 99999);
                    $('#left-nav').zIndex(200);
                }
            },
            {
                selector: '[data-href="#dashboard/2"]',
                position: 'right-center',
                title: '<h2>Heat Maps</h2>',
                content: '<p>This section will show you class counts per hour/day based on a user selected schedule.</p><p>You can view the "density" of up to five different schedules at the same time.</p><p>Lighter colors represent fewer classes while darker colors represent more classes.</p>',
                onSlide: function () {
                    //$('.tutorialize-slide-arrow').zIndex($('#left-nav').zIndex() + 1);
                    $('.tutorialize-slide').css( "z-index", 99999);
                    $('#left-nav').zIndex(200);
                }
            },
            {
                selector: '[data-href="#dashboard/3"]',
                position: 'right-center',
                title: '<h2>Schedule Explorer</h2>',
                content: '<p>This section will allow you to view an entire schedule on a single page. You can filter the schedule based on room, professor, class or class type.</p><p>You can also use this section to view the differences between two schedules. This can be done by selecting the "Diff" option from the "Type" drop-down on the left side of the screen.</p>',
                onSlide: function () {
                    //$('.tutorialize-slide-arrow').zIndex($('#left-nav').zIndex() + 1);
                    $('.tutorialize-slide').css( "z-index", 99999);
                    $('#left-nav').zIndex(200);
                }
            }
        ],
        onStart: function (index, data, dom) {
            $('body').keydown(function (e) {
                if (e.which == 27)
                    $.tutorialize.stop('vis-tut');
            });
        },
        onStop: function () {
            tour_ended = true;
            $('body').off('keydown');
        }
    }, 'vis-tut');
}