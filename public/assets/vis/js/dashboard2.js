var dashboard2 = (function () {

    "use strict";

    var vis_url = 'vis';
    var body;
    var spin;

    function createChart(selector, data) {
        if (data.length == 0) {
            return [];
        }
        var margin = {top: 0, right: 0, bottom: 0, left: 20},
        width = 200 - margin.left - margin.right,
            height = 675 - margin.top - margin.bottom,
            gridSize = 40,
            buckets = 9,
            colors = ["#ffffff", "#edf8b1", "#c7e9b4", "#7fcdbb", "#41b6c4", "#1d91c0", "#225ea8", "#253494", "#081d58"];
        var days = ["M", "T", "W", "H", "F", "S", "U"];
        var times = ["1a", "2a", "3a", "4a", "5a", "6a", "7a", "8a", "9a", "10a", "11a", "12p", "1p", "2p", "3p", "4p", "5p", "6p", "7p", "8p", "9p", "10p", "11p", "12a"];

        var shift = (8 - 1) * (gridSize) + 14;

        var colorScale = d3.scale.quantile()
            .domain([0, buckets - 1, d3.max(data, function (d) {
                    return d.value;
                })])
            .range(colors);

        var svg = d3.select(selector).append("svg")
            .attr("height", height + margin.left + margin.right)
            .attr("width", width + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        var timeLabels = svg.selectAll(".timeLabel")
            .data(times)
            .enter().append("text")
            .filter(function (d, i) {
                return ((i > 6) && (i < 18));
            })
            .text(function (d) {
                return d;
            })
            .attr("y", function (d, i) {
                return (i * gridSize) + 20;
            })
            .attr("x", 0)
            .style("text-anchor", "middle")
            //.attr("transform", "translate(" + gridSize / 2 + ", -6)")
            .attr("transform", "translate(-12," + gridSize / 2 + ")")
            .attr("class", function (d, i) {
                return ((i >= 7 && i <= 16) ? "timeLabel mono axis axis-worktime" : "timeLabel mono axis axis-worktime");
            });

        var heatMap = svg.selectAll(".hour")
            .data(data)
            .enter().append("rect")
            .filter(function (d) {
                return ((d.hour > 8) && (d.hour < 19));
            })
            .attr("y", function (d) {
                return (d.hour - 1) * (gridSize) - shift + 20;
            })
            .attr("x", function (d) {
                return (d.day - 1) * (gridSize * 0.5);
            })
            //.attr("ry", 4)
            //.attr("rx", 4)
            .attr("class", "hour bordered")
            .attr("width", gridSize / 2)
            .attr("height", gridSize / 2)
            .style("fill", colors[0]);

        heatMap.transition().duration(1000)
            .style("fill", function (d) {
                return colorScale(d.value);
            });

        heatMap.append("title").text(function (d) {
            return d.value;
        });

        //if (selector === '#d3-1') {
        var legend = svg.selectAll(".legend")
            .data([0].concat(colorScale.quantiles()), function (d) {
                return d;
            })
            .enter().append("g")
            .attr("class", "legend");

        legend.append("rect")
            .attr("x", function (d, i) {
                return (gridSize * 0.5) * i - 18;
            })
            .attr("y", 450 + 20)
            .attr("width", gridSize / 2)
            .attr("height", gridSize / 2)
            .style("fill", function (d, i) {
                return colors[i];
            });

        legend.append("text")
            .attr("class", "mono")
            .text(function (d) {
                return Math.round(d);
            })
            .attr("x", function (d, i) {
                return (gridSize * 0.5) * i - 14;
            })
            .attr("y", 450 + 35)
            .attr("class", function (d, i) {
                return "timeLabel mono axis axis-worktime";
            });

        var dayLabels = svg.selectAll(".dayLabel")
            .data(days)
            .enter().append("text")
            .text(function (d) {
                return d;
            })
            .attr("y", 20)
            .attr("x", function (d, i) {
                return i * (gridSize * 0.5) - 12;
            })
            .style("text-anchor", "end")
            //.attr("transform", "translate(-6," + gridSize / 1.5 + ")")
            .attr("transform", "translate(" + gridSize / 1.5 + ",-6)")
            .attr("class", function (d, i) {
                return ((i >= 0 && i <= 4) ? "dayLabel mono axis axis-workweek" : "dayLabel mono axis");
            });
        //}
    }

    function createSelect(tag_prefix, label, multiple) {
        var mult = "";
        var result = '<div class="form-group">';
        if (multiple) {
            mult += 'multiple="multiple"';
        }
        if (label.length > 0) {
            result += '<label for="' + tag_prefix + '-sel" class="vis-label">' + label + '</label>';
        }
        result += '<select id="' + tag_prefix + '-sel" ' + mult + '  class="vis2-select"></select></div>';
        return result;
    }

    function transformData(dataset) {
        if (dataset.length == 0) {
            return [];
        }
        var days = {"U": 0, "M": 1, "T": 2, "W": 3, "H": 4, "F": 5, "S": 6};
        // INIT array
        var val_array = new Array(672);
        var data = [];
        $.each(val_array, function (i, e) {
            val_array[i] = 0;
        });

        // Calc fields
        $.each(dataset, function (i, e) {
            if (e.days[0].length > 0) {
                $.each(e.days, function (j, d) {
                    var shift = days[d] * 96;
                    var slot = (parseFloat(e.starttm.substr(0, 2)) * 4) + Math.round((parseFloat(e.starttm.substr(2, 2)) / 60) * 4);
                    var len = (Math.round((parseFloat(e['length']) / 60) * 4) / 4);

                    for (var i = 0; i < len; i += 0.25) {
                        val_array[shift + slot + (i * 4)] += 1;
                    }
                });
            }
        });

        for (var i = 0; i < val_array.length; i++) {
            data.push({"day": Math.floor(i / 96) + 1, "hour": ((i % 96) / 4), "value": val_array[i]});
        }

        return data;
    }

    function yearSelectionHandler(sched, d3Select) {
        if(sched != 'None' && sched != '') {
            spin.spin();
            $(d3Select).append(spin.el);
        }
        console.log(d3Select);
        
        $.ajax({
            dataType: "json",
            url: vis_url + '/' + sched + '/2',
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

                createChart(d3Select, transformData(data));
                spin.stop();
            }
        });
    }

    function multiselectCallback(selector, d3Select) {
        $(selector).multiselect('setOptions', {
            onChange: function (event) {
                d3.select(d3Select + ' svg').remove();
                yearSelectionHandler($(selector).val(), d3Select);
            }
        });

    }

    function render() {
        body = $('body');
        spin = new Spinner({top:"342px"});

        $.ajax({
            dataType: "json",
            url: vis_url + '/list',
            success: function (data) {

                var html = '<div style="margin: 20px 20px 20px 20px;"><div id="d3-1" class="col-md-2"></div>\n\
                    <div id="d3-2" class="col-md-2"></div>\n\
                    <div id="d3-3" class="col-md-2"></div>\n\
                    <div id="d3-4" class="col-md-2"></div>\n\
                    <div id="d3-5" class="col-md-2"></div></div>';
                $("#content").html(html);

                $('#d3-1').append(createSelect('sched1', 'Schedule 1', false));
                $('#d3-2').append(createSelect('sched2', 'Schedule 2', false));
                $('#d3-3').append(createSelect('sched3', 'Schedule 3', false));
                $('#d3-4').append(createSelect('sched4', 'Schedule 4', false));
                $('#d3-5').append(createSelect('sched5', 'Schedule 5', false));

                // Change this to use the schedule selector
                var result = '<option value="None">None</option>';
                for (var i = 0; i < data.length; i++) {
                    result += '<option value="' + data[i]['id'] + '">' + data[i]['name'] + '</option>';
                }

                $('#sched1-sel').append(result);
                $('#sched2-sel').append(result);
                $('#sched3-sel').append(result);
                $('#sched4-sel').append(result);
                $('#sched5-sel').append(result);

                $('select').multiselect({
                    maxHeight: 300,
                    buttonWidth: '185px'
                });

                multiselectCallback('#sched1-sel', '#d3-1');
                multiselectCallback('#sched2-sel', '#d3-2');
                multiselectCallback('#sched3-sel', '#d3-3');
                multiselectCallback('#sched4-sel', '#d3-4');
                multiselectCallback('#sched5-sel', '#d3-5');
                
                $('#d3-1').append('<div id="spin-box"></div>');
            }
        });

    }

    return {
        render: render
    }

}());
