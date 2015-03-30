var dashboard1 = (function () {

    "use strict";

    // Currently selected dashboard values
    var chart1;
    var chart2;
    var scheduleMap = {};
    var vis_url = 'vis';

    function createNewSummaryChart(selector, data) {

        $.each(data, function (i, v) {
            scheduleMap[v.Year + '-' + v.Semester] = v.id;
        });

        var svg = dimple.newSvg(selector, 530, 600);
        // Create and Position a Chart
        var chart1 = new dimple.chart(svg, data);
        chart1.setBounds(80, 40, 420, 450);
        var x = chart1.addCategoryAxis("x", "Year");
        chart1.addMeasureAxis("y", "Count");

        // Order the x axis by date
        x.addOrderRule("Date");

        // Add a thick line with markers
        var lines = chart1.addSeries(["Semester"], dimple.plot.line);
        lines.lineWeight = 5;
        lines.lineMarkers = true;

        lines.addEventHandler("click", function (e) {
            return yearSelectionHandler(scheduleMap[e.xValue + '-' + e.seriesValue]);
        });
        lines.addEventHandler("mouseover", null);

        chart1.addLegend(180, 10, 360, 20, "right");

        // Draw the chart
        chart1.draw();
        return chart1;
    }

    function yearSelectionHandler(sched) {
        $.ajax({
            dataType: "json",
            url: vis_url + '/' + sched + '/1',
            success: function (data) {
                $('#chart2>.title').html('Total Classes by Prof in ' + data[0]['name']);
                chart2.data = data;
                chart2.draw(750);
            }
        });
    }

/*
    function sortObject(obj) {
        var arr = [];
        for (var prop in obj) {
            if (obj.hasOwnProperty(prop)) {
                arr.push({
                    'key': prop,
                    'value': obj[prop]
                });
            }
        }
        arr.sort(function (a, b) {
            return (a['key'] < b['key']) ? -1 : (a['key'] > b['key']) ? 1 : 0;
        });
        //arr.sort(function(a, b) { a.value.toLowerCase().localeCompare(b.value.toLowerCase()); }); //use this to sort as strings
        return arr; // returns array
    }
*/

    function createProfChart(selector, session) {
        var svg = dimple.newSvg(selector, 650, 600);
        chart2 = new dimple.chart(svg, []);
        chart2.setBounds(60, 30, 580, 450);
        var x = chart2.addCategoryAxis("x", "Professor");
        x.addOrderRule("Professor");
        chart2.addMeasureAxis("y", "Total");
        var s = chart2.addSeries("Type", dimple.plot.bar);
        s.addOrderRule("Type", true); 
        chart2.addLegend(0, 0, 650, 0, "right", s);

        chart2.draw();
        return chart2;
    }

    function render() {
        $.ajax({
            dataType: "json",
            url: vis_url + '/0/0',
            success: function (data) {

                var html =
                    '<div id="chart1" class="chart chart2">' +
                    '<div class="title">Class Counts by Year and Semester</div>' +
                    '<div class="graph"></div>' +
                    '</div>' +
                    '<div id="chart2" class="chart chart2">' +
                    '<div class="title"></div>' +
                    '<div class="graph"></div>' +
                    '</div>';

                $("#content").html(html);

                chart1 = createNewSummaryChart('#chart1', data);

                // Since the JSON results are returned from the database sorted, pick the very last item
                chart2 = createProfChart('#chart2', yearSelectionHandler(data[data.length - 1].id));
            }
        });
    }

    return {
        render: render
    };

}());