var dashboard1 = (function () {

    "use strict";

    // Currently selected dashboard values
    var chart1,
            chart2,
            selectedYear = 2014,
            selectedSemester = "FALL";

    function createSummaryChart(selector, dataset) {

        for (var i = 0; i < dataset.length; i++) {
            for (var j = 0; j < dataset[i].data.length; j++) {
                dataset[i].data[j]['semester'] = dataset[i]['semester'];
            }
        }

        var data = {
            "xScale": "linear",
            "yScale": "linear",
            "main": dataset
        },
        options = {
            "axisPaddingLeft": 0,
            "paddingLeft": 25,
            "paddingRight": 0,
            "axisPaddingRight": 0,
            "axisPaddingTop": 5,
            "interpolation": "linear",
            "tickHintX": 20,
            "click": yearSelectionHandler
        },
        legend = d3.select(selector).append("svg")
                .attr("class", "legend")
                .selectAll("g")
                .data(dataset)
                .enter()
                .append("g")
                .attr("transform", function (d, i) {
                    return "translate(" + (64 + (i * 84)) + ", 0)";
                });

        legend.append("rect")
                .attr("width", 18)
                .attr("height", 18)
                .attr("class", function (d, i) {
                    return 'color' + i;
                });

        legend.append("text")
                .attr("x", 24)
                .attr("y", 9)
                .attr("dy", ".35em")
                .text(function (d, i) {
                    return dataset[i].semester;
                });

        return new xChart('line-dotted', data, selector + " .graph", options);
    }

    function createProfBreakdownChart(selector, dataset) {

        var data = {
            "xScale": "ordinal",
            "yScale": "linear",
            "type": "bar",
            "main": dataset
        },
        options = {
            "axisPaddingLeft": 0,
            "axisPaddingTop": 5,
            "paddingLeft": 20
        };

        return new xChart('bar', data, selector + " .graph", options);

    }

    function yearSelectionHandler(d, i) {
        selectedYear = d.x;
        selectedSemester = d.semester;
        var data = {
            "xScale": "ordinal",
            "yScale": "linear",
            "type": "bar",
            "main": getProfBreakdownForYearSemester(selectedYear + '-' + selectedSemester)
        };
        $('#chart2>.title').html('Total Classes by Prof in ' + selectedSemester + " " + selectedYear);
        chart2.data = results[selectedYear + '-' + selectedSemester];
        chart2.draw(500);
    }

    function createDimpleChart(selector, session) {
        var svg = dimple.newSvg(selector, 650, 700);
        chart2 = new dimple.chart(svg, results[session]);
        chart2.setBounds(60, 30, 580, 450);
        var x = chart2.addCategoryAxis("x", "Professor");
        x.addOrderRule("Professor");
        chart2.addMeasureAxis("y", "Total");
        chart2.addSeries("type", dimple.plot.bar);
        chart2.draw();
        return chart2;
    }

    function getProfBreakdownForYearSemester(session) {
        // Transform data to single year/semester
        var result = [];
        for (var i = 0; i < results[session].length; i++) {
            result.push({x: results[session][i].Country, y: results[session][i].Total});
        }
        return [
            {
                "className": ".medals",
                "data": result
            }
        ];
    }

    function render() {

        var html =
                '<div id="chart1" class="chart chart2">' +
                '<div class="title">Class Counts by Year and Semester</div>' +
                '<div class="graph"></div>' +
                '</div>' +
                '<div id="chart2" class="chart chart2">' +
                '<div class="title">Total Classes by Prof in FALL 2014</div>' +
                '<div class="graph"></div>' +
                '</div>';

        $("#content").html(html);

        chart1 = createSummaryChart('#chart1', summary);
        chart2 = createDimpleChart('#chart2', (selectedYear + '-' + selectedSemester));
    }

    return {
        render: render
    };

}());
