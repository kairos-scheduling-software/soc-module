var dashboard3 = (function () {

    "use strict";

    // Currently selected dashboard values
    var chart1;

    var gridMargin = {top: 20, right: 5, bottom: 5, left: 70};
    var svgWidth = 1000;  // - gridMargin.left - gridMargin.right;
    var svgHeight = 1000;  // - gridMargin.top - gridMargin.bottom;

    var gridClassSelected = "";
    var gridClassHighlightColor = "#33FF33";

    var gridColors = {};
    var gridColorCounter = 0;
    var gridColorsIndex = {};

    var svg;
    var container;
    var tip;

    // Colors
    var scale = chroma.scale(['#99AAFF', '#BBDDCC', '#8888AA', '#887711', '#557755', '#118833', '#7F3F6A', '#992266', '#3D3F4C', '#0027E5']).mode('lab'); //chroma.scale(['navy']); //.mode('lab');

    // Start with popover hidden
    $('#po-d3').hide();

    var d3Zoom = d3.behavior.zoom()
            .scaleExtent([0.25, 7])
            .on("zoom", d3Zoomed);

    var weekdays = [['Sunday', 'Su'], ['Monday', 'M'], ['Tuesday', 'T'], ['Wednesday', 'W'], ['Thursday', 'Th'], ['Friday', 'F'], ['Saturday', 'Sa']];

    function d3Zoomed() {
        container.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
    }

    /* Functions to create the individual charts involved in the dashboard */
    function init() {

        svgWidth =  $('#content').width() - 300;
        svgHeight = $('#left-nav').height() - 50;

        //Make an SVG Container
        svg = d3.select("#d3").append("svg")
                .attr("width", svgWidth + gridMargin.left + gridMargin.right)
                .attr("height", svgHeight + gridMargin.top + gridMargin.bottom)
                .append("g")
                .attr("transform", "translate(" + gridMargin.left + "," + gridMargin.top + ")");

        // Zoom only works when it is over a rendered item. Render a white background.
        svg.append("rect")
                .attr("x", 0 - gridMargin.left)
                .attr("y", 0 - gridMargin.top)
                .attr("width", "100%")
                .attr("height", "100%")
                .attr("fill", "#FFFFFF")
                .call(d3Zoom);

        container = svg.append("g");

        tip = d3.tip().attr('class', 'd3-tip').html(function (d) {
            return  '<table style="width:100%">' +
                        '<tr>' +
                            '<td> Class: </td>' +
                            '<td><span style="color:red">' + d.class.replace("_", " ").replace("_", " ") + '</span></td>' +
                        '</tr>' +
                        '<tr>' +
                            '<td> Title: </td>' +
                            '<td><span style="color:red">' + d.title + '</span></td>' +
                        '</tr>' +
                        '<tr>' +
                            '<td> Type: </td>' +
                            '<td><span style="color:red">' + d.class_type + '</span></td>' +
                        '</tr>' +
                        '<tr>' +
                            '<td> Room: </td>' +
                            '<td><span style="color:red">' + d.room + '</span></td>' +
                        '</tr>' +
                        '<tr>' +
                            '<td> Start: </td>' +
                            '<td><span style="color:red">' + d.starttm + '</span></td>' +
                        '</tr>' +
                        '<tr>' +
                            '<td> Length: &nbsp; </td>' +
                            '<td><span style="color:red">' + d.length + ' minutes</span></td>' +
                        '</tr>' +
                        '<tr>' +
                            '<td> Day: </td>' +
                            '<td><span style="color:red">' + d.days + '</span></td>' +
                        '</tr>' +
                    '</table>'

                    ;
        });
        svg.call(tip);
    }

    // Other functions that need to be removed
    function getClassStringId(classname) {
        return classname.split(" ").join("_");
    }

    // The magic function.
    function getGridScreenCoords(x, y, ctm) {
        var xn = ctm.e + x * ctm.a;
        var yn = ctm.f + y * ctm.d;
        return {x: xn, y: yn};
    }

    function getGridColor(cname) {
        if (!gridColors[cname]) {
            gridColors[cname] = scale((gridColorsIndex[cname]) / ((gridColorCounter - 1))).hex();
        }
        return gridColors[cname];
    }


    function createChart(selector, dataset, doInit) {
        if (doInit) {
            init();
        }

        var boxWidth = 12; //10
        var boxHeight = 50.0;
        var daysSkip = 1;
        var numDays = 5; // 7
        var dayBoxes = 0;

        var firstTimeOfDay = 7.00;
        var lastTimeOfDay = 21.00;
        var roomCounter = 0;
        var classes = new Array();
        var prof = new Array();
        var class_types = new Array();
        var room_list = new Array();
        var rooms = {};
        var days = {};

        var data = [];

        svg.selectAll('g').remove();
        container = svg.append("g");

        //d3Zoom.scale(1);
        //d3Zoom.translate([0, 0]);
        container.attr('transform', 'translate(' + d3Zoom.translate() + ') scale(' + d3Zoom.scale() + ')');

        data = [];

        if (!dataset)
            return;
        // loop over data, get room counts
        $.each(dataset, function (i, val) {
            var day;
            var class_name;
            if (val.days[0] != "" && val.room != "") {

                //console.log(JSON.stringify(val));

                // Pre-populate array
                getCol(val['room']);

                class_name = getClassStringId(val.name);
                setClassColorIndex(val.class_type);

                for (var j = 0; j < val.days.length; j++) {
                    // Kind of ugly... This can be done better.
                    if (val.days[j] === 'M') {
                        day = 1;
                    } else if (val.days[j] === 'T') {
                        day = 2;
                    } else if (val.days[j] === 'W') {
                        day = 3;
                    } else if (val.days[j] === 'H') {
                        day = 4;
                    } else if (val.days[j] === 'F') {
                        day = 5;
                    } else if (val.days[j] === 'S') {
                        day = 6;
                    } else if (val.days[j] === 'U') {
                        day = 7;
                    }

                    var point = {
                        "starttm": val.starttm,
                        "length": val.length,
                        "days": val.days,
                        "day": day,
                        "name": val.name,
                        "class_type": val.class_type,
                        "title": val.title,
                        "room": val.room,
                        "class": class_name,
                        "cname": class_name.substring(0, class_name.indexOf("-")) // Used for colors
                    };



                    //console.log(class_name.substring(0, class_name.indexOf("-")));
                    data.push(point);
                }

                setClassColorIndex(val.class_type);

                // Kind of ugly...
                if (val.day >= 0 && val.day <= 7) {
                    if (!days[val.name]) {
                        days[val.name] = weekdays[val.day][1];
                    } else {
                        days[val.name] += weekdays[val.day][1];
                    }
                }
            }

        });

        dayBoxes = roomCounter;
        boxWidth = Math.round((svgWidth / numDays) / roomCounter);
        if (boxWidth < 12) {
            boxWidth = 12;
        }

        // Calculate values so we don't have to do it each time a frame is rendered
        $.each(data, function (i, val) {
            val['col'] = getCol(val.room);
            val['color'] = getGridColor(val.class_type);
            val['x'] = (((val.day * dayBoxes) + val['col']) * boxWidth) - (daysSkip * boxWidth * dayBoxes);
            val['y'] = ((timeToNumber(val.starttm) - firstTimeOfDay) * boxHeight);
            val['height'] = (minutesToNumber(val.length) * boxHeight);
            //val['id'] = val.id; // <-- this does nothing val['id'] and val.id are the same field in the same object

            if (val.day == 0) {
                val['tipDir'] = 'w';
            } else if (val.day == 6) {
                val['tipDir'] = 'e';
            } else if ((val['y'] / boxHeight) < 1) {
                val['tipDir'] = 's';
            } else {
                val['tipDir'] = 'n';
            }
        });

        $.each(gridColors, function (i, val) {
            class_types.push(i);
        });

        class_types.sort();

        // Classes
        var class_sel = $("#class-type-sel");
        $.each(class_types, function (i, val) {
            class_sel.append('<option vlaue=' + i + '>' + val + '</option>');
        });

        // Rooms
        $.each(rooms, function (i, val) {
            room_list.push(i);
        });

        room_list.sort();

        var rooms_sel = $("#rooms-sel");
        rooms_sel.html('');
        console.log(roomCounter);
        $.each(rooms, function (key, val) {
            console.log(key, val);
            rooms_sel.append('<option vlaue=' + val + '>' + key + '</option>');
        });

        $('select').multiselect('destroy')
        $('select').multiselect({
            //    width: 200
            maxHeight: 300,
            buttonWidth: '175px'
        });

        // The +0.1 makes it draw the very last bar. +1 causes a little bit of the axis to hang over
        var width = boxWidth * dayBoxes * numDays + 0.1;
        var height = (lastTimeOfDay - firstTimeOfDay) * boxHeight + 0.1;

        // X bars
        container.append("g")
                .attr("class", "x_axis")
                .selectAll("line")
                .data(d3.range(0, width, boxWidth))
                .enter().append("line")
                .attr("x1", function (d) {
                    return d;
                })
                .attr("y1", 0)
                .attr("x2", function (d) {
                    return d;
                })
                .attr("y2", height + 50)
                .attr("stroke", function (x) {
                    if (x % (boxWidth * dayBoxes) == 0) {
                        return "black";
                    }
                    return "gray";
                })
                .attr("stroke-width", function (x) {
                    if (x % (boxWidth * dayBoxes) == 0) {
                        return 1;
                    }
                    return 0.3;
                });

        // X
        container.selectAll(".x_axis")
                .data(d3.range(0, width, boxWidth * dayBoxes))
                .enter().append("text")
                .attr("y", 0)
                .attr("x", function (d) {
                    return (d - ((boxWidth * dayBoxes) / 2));
                })
                .attr("font-size", 12)
                .attr("dx", "0em")
                .attr("dy", "-0.5em")
                .attr("text-anchor", "middle")
                //.attr('class', 'name')
                .text(function (d, i) {
                    return weekdays[(i + daysSkip) - 1][0];
                });

        // X - Rooom labels
        container
                .selectAll(".x_axis_labels")
                .data(d3.range(0, width - 1, boxWidth))
                .enter()
                .append('g')
                .attr("transform", function (d) {
                    return "translate(" + (d + (boxWidth * 0.5)) + "," + 25 + ") rotate(90,0,0)";
                })
                .append("text")
                .attr("y", 0)
                .attr("x", 0)
                .attr("font-size", 7)
                .attr("dx", "0em")
                .attr("dy", "0.35em")
                .attr("text-anchor", "middle")
                //.attr('class', 'name')
                .text(function (d, i) {
                    return room_list[i % dayBoxes];
                });


        // Y bars
        container.append("g")
                .attr("class", "y_axis")
                .selectAll("line")
                .data(d3.range(0, height + 50, boxHeight))
                .enter().append("line")
                .attr("x1", 0)
                .attr("y1", function (d) {
                    return d;
                })
                .attr("x2", width)
                .attr("y2", function (d) {
                    return d;
                })
                .attr("stroke", function (x, y) {
                    if (x <= 50 || x >= (height + 49)) {
                        return "black";
                    }
                    return "gray";
                })
                .attr("stroke-width", function (x, y) {
                    if (x <= 50 || x >= (height + 49)) {
                        return 1;
                    }
                    return 0.3;
                })
                ;

        container.selectAll(".y_axis")
                .data(d3.range(0, height + 50, boxHeight))
                .enter().append("text")
                .attr("x", 0)
                .attr("y", function (d) {
                    return d - (boxHeight) + 3 + 50;
                })
                .attr("font-size", 12)
                .style("text-anchor", "end")
                .attr("dx", "-5px")
                .attr("dy", "0em")
                .attr("text-anchor", "middle")
                .text(function (d, i) {
                    var hour = (i - 1) + firstTimeOfDay;
                    if (hour >= 13) {
                        return hour - 12 + ":00pm";
                    }
                    return hour + ":00am";
                });

        // Draw blocks from JSON object
        var blocks = container.selectAll("g.data")
                .data(data)
                .enter()
                .append("g") // SWITCH BACK TO RECT
                .filter(function (d) {
                    return (d.x === d.x && d.y === d.y);
                }) // Filter out classes with no time assigned to them (NaN != NaN)
                .attr("x", function (d) {
                    return d.x;
                })
                .attr("y", function (d) {
                    return d.y;
                })
                .attr("width", function (d) {
                    return boxWidth;
                })
                .attr("height", function (d) {
                    return d.height;
                })
                .on('mouseover', function (d) {
                    d3.selectAll("." + d.class).style("fill", gridClassHighlightColor);
                    tip.direction(d.tipDir);
                    tip.show(d);
                })
                .on('mouseout', function (d) {
                    tip.hide();
                    d3.selectAll("." + d.class).style("fill", d.color);
                });

        var gText = blocks
                .append("g")
                .attr("transform", function (d) {
                    return "translate(" + d.x + "," + (d.y + 50) + ") rotate(90,0,0)";
                })
                .attr("class", function (d) {
                    return "clText";
                })
                .append("text")
                .attr("x", function (d) {
                    return (d.height * 0.5);
                })
                .attr("y", function (d) {
                    return 0;
                })
                .attr("font-size", 5)
                .attr("dx", "0em")
                .attr("dy", "-0.75em")
                .text(function (d, i) {
                    return d.class.replace("_", " ").replace("_", " ");
                })
                .style("text-anchor", "middle")
                .style("fill", "#000000");

        // Set block attributes
        var blockAttributes = blocks
                .append("rect")
                //.filter(function (d) {
                //    return (d.x === d.x && d.y === d.y);
                //}) // Filter out classes with no time assigned to them (NaN != NaN)
                .attr("class", function (d) {
                    return d.class;
                })
                .attr("x", function (d) {
                    return d.x;
                })
                .attr("y", function (d) {
                    return d.y + 50;
                })
                .attr("width", function (d) {
                    return boxWidth;
                })
                .attr("height", function (d) {
                    return d.height;
                })
                .style("fill-opacity", 0.3)
                .style("fill", function (d) {
                    return d.color;
                })
                .style("stroke", function (d) {
                    return "#000000";
                })
                .attr("stroke-width", 0.3)
                .attr("rx", 3) // set the x corner curve radius
                .attr("ry", 3) // set the y corner curve radius
                .on("click", function (d, i) {
                    d3.event.stopPropagation();
                })
                .on("dblclick", function (d, i) {
                    var xOffset = boxWidth * d3Zoom.scale();

                    // Find where the div is located on the screen
                    var offset = $('#' + 'd3').offset();

                    // Get coords, translate them to svg coordinates
                    var ctm = this.getCTM();
                    var coords = getGridScreenCoords(d.x, d.y, ctm);

                    // Height of elements
                    var poHeight = $('#po-d3').height();
                    var svgHeight = $('#d3').height();

                    // Offset y value based on where we are on the screen
                    var yOffset = 0 ; //= (coords.y / svgHeight) * poHeight;

                    var final_y = (coords.y + offset.top) - yOffset + 50;
                    var final_x = (coords.x + offset.left + xOffset);
                    var arrow_y = (((d.height) * d3Zoom.scale()) / 2) + yOffset;

                    if (final_y < offset.top + 3) {
                        final_y = offset.top + 3;
                    }

                    if (arrow_y < 15) {
                        arrow_y = 15;
                    }

                    // ADD CODE HERE TO FLIP WHICH DIRECTION THE POPOVER DISPLAYS ON THE X AXIS
                    gridClassSelected = d.name;
                    // Set popover title
                    $('#po-d3-name').html(d.name);
                    $('#po-d3-title').html(d.title);
                    $('#po-room').html(d.room);
                    $('#po-dtm').html('[' + d.days + "], " + makeTimePretty(d.starttm) + ", " + d.length + " min");
                    $('#message').val("");
                    $('#event_id').val(d.id);

                    $('#po-d3').show();

                    // Set popover position
                    $('#po-d3').css('left', final_x + 'px');
                    $('#po-d3').css('top', (final_y) + 'px');

                    // Put arrow in correct spot
                    $('#po-d3-arrow').css('top', (arrow_y) + 'px');
                    $('#po-d3-arrow').hide();
                });

        function minutesToNumber(minutes) {
            return ((1.666) * minutes) / 100; // 100/60 = 1.666
        }

        function numberToMinutes(n) {
            return ((1.666) / n) * 100; // 100/60 = 1.666
        }

        function timeToNumber(dtm) {
            var hour = parseFloat(dtm.substring(0, 2));
            var minutes = minutesToNumber(parseFloat(dtm.substring(2)));
            return hour + minutes;
        }

        function makeTimePretty(time) {
            var format = d3.time.format("%H%M");
            var pretty = d3.time.format("%I:%M %p");
            return pretty(format.parse(time));
        }

        function getCol(room) {
            //console.log(room);
            if (room === "")
                return;
            if (rooms[room] == null) {
                rooms[room] = roomCounter;
                //console.log(room + ' ++> ' + rooms[room]);
                roomCounter++;
            }
            //console.log(room + ' --> ' + rooms[room]);
            return rooms[room];
        }

        function setClassColorIndex(cname) {
            if (gridColorsIndex[cname] == null) {
                gridColorsIndex[cname] = gridColorCounter++;
            }
            return gridColorsIndex[cname];
        }

        $('#po-d3-ok').click(function (e) {
            // Submit fields via JSON here.

            // This goes in the AJAX success function
            $('#po-d3').hide();

            // update the view based on new data received
            console.log("send: " + JSON.stringify(constraints[gridClassSelected]));

        });

        // Click close button
        $('#po-d3-close').click(function (e) {
            $('#po-d3').hide();
        });

     //set up the ticket submit button
     $('#log_ticket').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var postData = {event_id: $('#event_id').val(), message: $('#message').val() };
        var url = form.attr("action");

        $.ajax({
            url:        url,
            type:       "POST",
            data:       postData,
            success:    function(data, textStatus, jqXHR)
            {
                $('#po-d3').hide();
            },
            error:      function(jqXHR, textStatus, errorThrown) {
                var message = $.parseJSON(jqXHR.responseText);
                alert(message.error);
                // TODO:  bootstrap error message
            }
        });

        return false;
        });
    }

    function render() {

        $("#content").load("assets/vis/vis3Filt.html", function () {
            //chart1 =
            createChart('#d3', vis_data['2000-FALL'], true);

            $('select').multiselect({
                maxHeight: 300,
                buttonWidth: '175px'
            });

            $(".vis-select").change(function () {
                console.log($("#year-sel").val() + '-' + $("#semester-sel").val(), false);
                createChart('#d3', vis_data[$("#year-sel").val() + '-' + $("#semester-sel").val()]);
            });
        });
    }

    return {
        render: render
    };

}());
