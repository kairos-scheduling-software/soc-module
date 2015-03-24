var dashboard1 = (function () {

    "use strict";

    // Currently selected dashboard values
    var chart1;
    var chart2;
    var selectedYear = 2014;
    var selectedSemester = "FALL";

    function createNewSummaryChart(selector, data) {
          var svg = dimple.newSvg(selector, 530, 600);
          // Create and Position a Chart
          var chart1 = new dimple.chart(svg, data);
          chart1.setBounds(80, 40, 420, 450);
          var x = chart1.addCategoryAxis("x", "Year")
          chart1.addMeasureAxis("y", "Count");

          // Order the x axis by date
          x.addOrderRule("Date");

          // Add a thick line with markers
          var lines = chart1.addSeries("Semester", dimple.plot.line);
          lines.lineWeight = 5;
          lines.lineMarkers = true;

          lines.addEventHandler("click", function(e) {
              return yearSelectionHandler(e.xValue + '-' + e.seriesValue);
          });
          lines.addEventHandler("mouseover", null);

          chart1.addLegend(180, 10, 360, 20, "bottom");

          // Draw the chart
          chart1.draw();
          return chart1;
    }

    function yearSelectionHandler(sched) {
        $('#chart2>.title').html('Total Classes by Prof in ' + sched);
        chart2.data = results[sched];
        chart2.draw(750);
    }

    function createDimpleChart(selector, session) {
        var svg = dimple.newSvg(selector, 650, 600);
        chart2 = new dimple.chart(svg, results[session]);
        chart2.setBounds(60, 30, 580, 450);
        var x = chart2.addCategoryAxis("x", "Professor");
        x.addOrderRule("Professor");
        chart2.addMeasureAxis("y", "Total");
        chart2.addSeries("type", dimple.plot.bar);
        chart2.draw();
        return chart2;
    }

    function render() {

        var html =
                '<div id="chart1" class="chart chart2">' +
                '<div class="title">Class Counts by Year and Semester</div>' +
                '<div class="graph"></div>' +
                '</div>' +
                '<div id="chart2" class="chart chart2">' +
                '<div class="title">Total Classes by Prof in ' + selectedSemester + " " + selectedYear + '</div>' +
                '<div class="graph"></div>' +
                '</div>';

        $("#content").html(html);

        chart1 = createNewSummaryChart('#chart1', summary);
        chart2 = createDimpleChart('#chart2', (selectedYear + '-' + selectedSemester));
    }

    return {
        render: render
    };

}());