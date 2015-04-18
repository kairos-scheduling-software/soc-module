var dashboard3 = (function () {

    "use strict";

    var vis_url = 'vis';
    var body;
    var spin;

    var gridMargin = {top: 20, right: 5, bottom: 5, left: 70};
    var svgWidth = 1000;
    var svgHeight = 1000;

    var gridClassSelected = "";
    var gridClassHighlightColor = "#33FF33"; //'#67C8FF';

    var gridColors = {};
    var gridColorCounter = 0;
    var gridColorsIndex = {};

    var svg;
    var container;
    var tip;

    var diffSelected = false;

    // Colors
    var scale = chroma.scale(['#99AAFF', '#BBDDCC', '#8888AA', '#887711', '#557755', '#118833', '#7F3F6A', '#992266', '#3D3F4C', '#0027E5']).mode('lab');

    // Start with popover hidden
    $('#po-d3').hide();

    var d3Zoom = d3.behavior.zoom()
        .scaleExtent([0.25, 7])
        .on("zoom", d3Zoomed);

    var weekdays = [['Sunday', 'Su'], ['Monday', 'M'], ['Tuesday', 'T'], ['Wednesday', 'W'], ['Thursday', 'Th'], ['Friday', 'F'], ['Saturday', 'Sa']];

    function d3Zoomed() {
        // Remove the tip when zooming or dragging
        d3.select('.d3-tip').style('opacity', 0).style('pointer-events', 'none');
        container.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
    }

    /* Functions to create the individual charts involved in the dashboard */
    function init(selector) {
        var navbar_height = $('#custom_navbar').height() || 0;
        svgWidth = viewportSize.getWidth() - 200 - 1;
        svgHeight = viewportSize.getHeight() - navbar_height - 1;

        //Make an SVG Container
        svg = d3.select(selector).append("svg")
            .attr("width", svgWidth)
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
            .on('click', function () {
                // Remove active tip
                d3.select('.d3-tip').style('opacity', 0).style('pointer-events', 'none');

                // Unselect text on click. Really annoying.
                if (window.getSelection) {
                    if (window.getSelection().empty) {  // Chrome
                        window.getSelection().empty();
                    } else if (window.getSelection().removeAllRanges) {  // Firefox
                        window.getSelection().removeAllRanges();
                    }
                } else if (document.selection) {  // IE?
                    document.selection.empty();
                }
            }).call(d3Zoom);

        container = svg.append("g");

        $(".d3-tip").remove();
        tip = d3.tip().attr('class', 'd3-tip').html(function (d) {
            return  '<table style="width:100%">' +
                '<tr>' +
                '<td> Class: </td>' +
                '<td><span style="color:red">' + d.name + '</span></td>' +
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
                '<td> Professor: &nbsp; </td>' +
                '<td><span style="color:red">' + d.mprof + '</span></td>' +
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
                (d.num_classes > 1 ? ('</tr><tr><td> Meets With:&nbsp;</td><td><span style="color:red">' + d.meets_with + '</span></td>') : '') +
                '</tr>' +
                '</table>';
        });
        svg.call(tip);

        window.onresize = function (event) {
            var navbar_height = $('#custom_navbar').height() || 0;
            svgWidth = viewportSize.getWidth() - 200 - 1;
            svgHeight = viewportSize.getHeight() - navbar_height - 1;

            $("#left-nav").css("top", navbar_height + "px");
            $("#content").css("top", navbar_height + "px");

            svg.attr("width", svgWidth + "px").attr("height", svgHeight + "px");
            d3.select("#d3").attr("width", svgWidth + "px").attr("height", svgHeight + "px").attr("top", navbar_height + "px");
            d3.select("#d3").select('svg').attr("width", svgWidth + "px").attr("height", svgHeight + "px");
        };
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
            init(selector);
        }

        // Get rid of any multiselect that is still active
        //$('select').multiselect('destroy');

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
        var mprof = {};
        var rooms = {};
        var days = {};
        var cnames = {};

        var isDiff = false;

        var data = [];

        svg.selectAll('g').remove();
        container = svg.append("g");
        container.attr('transform', 'translate(' + d3Zoom.translate() + ') scale(' + d3Zoom.scale() + ')');

        data = [];

        if (!dataset)
            return;

        if (dataset[0].diff !== undefined) {
            isDiff = true;
        }

        // loop over data, get room counts
        $.each(dataset, function (i, val) {
            var day;
            var class_name;
            if (val.days[0] != "" && val.room != "") {
                // Pre-populate array
                getCol(val['room']);

                class_name = getClassStringId(val.name);
                setClassColorIndex(val.class_type);

                if (mprof[val.main_prof] == null) {
                    mprof[val.main_prof] = 1;
                }

                for (var j = 0; j < val.days.length; j++) {
                    day = dayMap[val.days[j]];

                    var cname_ind = class_name.indexOf("-") < 0 ? class_name.length : class_name.indexOf("-");
                    var point = {
                        "id": val.id,
                        "starttm": val.starttm,
                        "length": val.length,
                        "days": val.days,
                        "day": day,
                        "name": val.name,
                        "class_type": val.class_type,
                        "title": val.title,
                        "room": val.room,
                        "class": class_name,
                        "cname": class_name.substring(0, cname_ind), // Used for colors
                        "mprof": val.main_prof,
                        "num_classes": val.num_classes,
                        "meets_with": brArray(val.meets_with.split('^'))
                    };

                    if (isDiff) {
                        point['diff'] = val.diff;
                    }

                    if (cnames[point.cname] == null) {
                        cnames[point.cname] = 1;
                    }

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

        // Do rooms here so we get the correct column alignment
        $.each(rooms, function (i, val) {
            room_list.push(i);
        });

        room_list.sort();

        roomCounter = 0;
        rooms = {};

        var rooms_sel = $("#rooms-sel");
        rooms_sel.html('');

        $.each(room_list, function (key, val) {
            getCol(val);
            rooms_sel.append('<option value=' + val.replace(/[^A-Z0-9]/g, '_') + '>' + val + '</option>');
        });

        // Calculate values so we don't have to do it each time a frame is rendered
        $.each(data, function (i, val) {
            val['col'] = getCol(val.room);
            val['color'] = getGridColor(val.class_type);
            val['x'] = (((val.day * dayBoxes) + val['col']) * boxWidth) - (daysSkip * boxWidth * dayBoxes);
            val['y'] = ((timeToNumber(val.starttm) - firstTimeOfDay) * boxHeight);
            val['height'] = (minutesToNumber(val.length) * boxHeight);
            //val['id'] = val.id; // <-- this does nothing val['id'] and val.id are the same field in the same object

            if (isDiff) {
                if (val.diff == 'p') {
                    val['x'] = val.x + (boxWidth / 2);
                    val['color'] = 'green';
                } else if (val.diff == 'm') {
                    val['color'] = 'red';
                } else {
                    val['color'] = 'gray';
                }
            }

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
        class_sel.html('');
        $.each(class_types, function (i, val) {
            class_sel.append('<option value=' + val.replace(/[^A-Z0-9]/g, '_') + '>' + val + '</option>');
        });
        class_sel.multiselect('rebuild');
        class_sel.multiselect('selectAll', false);
        class_sel.multiselect('refresh');

        rooms_sel.multiselect('rebuild');
        rooms_sel.multiselect('selectAll', false);
        rooms_sel.multiselect('refresh');

        $.each(mprof, function (i, val) {
            prof.push(i);
        });
        prof.sort();

        var prof_sel = $("#prof-sel");
        prof_sel.html('');

        $.each(prof, function (key, val) {
            prof_sel.append('<option value=' + val.replace(/[^A-Z0-9]/g, '_') + '>' + val + '</option>');
        });

        prof_sel.multiselect('rebuild');
        prof_sel.multiselect('selectAll', false);
        prof_sel.multiselect('refresh');

        $.each(cnames, function (i, val) {
            classes.push(i);
        });
        classes.sort();

        var class_sel = $("#class-sel");
        class_sel.html('');

        $.each(classes, function (key, val) {
            class_sel.append('<option value=' + val + '>' + val.replace(/[^A-Z0-9]/g, ' ') + '</option>');
        });

        class_sel.multiselect('rebuild');
        class_sel.multiselect('selectAll', false);
        class_sel.multiselect('refresh');

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
                return bottomHeightLimit(d.y, d.height);
            })
            .attr("class", function (d) {
                return d.room.replace(/[^A-Z0-9]/g, '_') + ' ' + d.class_type.replace(/[^A-Z0-9]/g, '_') + ' ' + d.mprof.replace(/[^A-Z0-9]/g, '_') + ' cls_id_' + d.id;
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
                if (isDiff) {
                    if (d['diff'] != 'e') {
                        return 4;
                    }
                }

                if (boxWidth > 12) {
                    return 1 - ((boxWidth - 12) / 2);
                }
                return 1;
            })
            .attr("font-size", 5)
            .attr("dx", "0em")
            .attr("dy", "-1em")
            .text(function (d, i) {
                return (d.num_classes > 1 ? ('* ' + d.name + ' *') : d.name);
            })
            .style("text-anchor", "middle")
            .style("fill-opacity", 0)
            .style("fill", "#000000")
            .transition().duration(750)
            .style("fill-opacity", 1);

        // Set block attributes
        var blockAttributes = blocks
            .append("rect")
            //.filter(function (d) {
            //    return (d.x === d.x && d.y === d.y);
            //}) // Filter out classes with no time assigned to them (NaN != NaN)
            .attr("class", function (d) {
                return 'cls_id_' + d.id;
            })
            .style("fill-opacity", 0)
            .attr("x", function (d) {
                return d.x;
                //return d.x + (boxWidth / 2);
            })
            .attr("y", function (d) {
                return d.y + 50;
            })
            .attr("width", function (d) {
                if (isDiff) {
                    if (d['diff'] != 'e') {
                        return (boxWidth / 2)
                    }
                }
                return boxWidth;
            })
            .attr("height", function (d) {
                return bottomHeightLimit(d.y, d.height);
            })
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
                var yOffset = 0; //= (coords.y / svgHeight) * poHeight;

                var final_y = (coords.y + offset.top) - yOffset + 50;
                var final_x = (coords.x + offset.left + xOffset);
                var arrow_y = (((d.height) * d3Zoom.scale()) / 2) + yOffset;

                if (final_y < offset.top + 3) {
                    final_y = offset.top + 3;
                }

                if (arrow_y < 15) {
                    arrow_y = 15;
                }

                if ((final_y + poHeight) > svgHeight) {
                    final_y = svgHeight - 350;
                }

                if ((final_x + 350) > width) {
                    final_x = final_x - $('#po-d3').width() - (xOffset * 3);
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
                $('#po-d3').css('top', final_y + 'px');
            })
            .on('mouseover', function (d) {
                d3.selectAll(".cls_id_" + d.id).style("fill", gridClassHighlightColor).style("fill-opacity", 0.6);

                var ctm = this.getCTM();
                var coords = getGridScreenCoords(d.x, d.y, ctm);

                var wp = coords.x / svgWidth;
                var hp = coords.y / svgHeight;

                var tipDir = '';

                if (hp >= 0.85) {
                    tipDir = 'n';
                } else if (hp <= 0.15) {
                    tipDir = 's';
                } else {
                    tipDir = 'n';
                }

                if (wp >= 0.85) {
                    tipDir += 'w';
                } else if (wp <= 0.15) {
                    tipDir += 'e';
                }

                tip.direction(tipDir);
                tip.show(d);
            })
            .on('mouseout', function (d) {
                tip.hide();
                d3.selectAll(".cls_id_" + d.id).style("fill", d.color).style("fill-opacity", 0.3);
                ;
            })
            .style("stroke-opacity", 0)
            .transition().duration(750)
            .style("fill-opacity", 0.3)
            .style("fill", function (d) {
                return d.color;
            })
            .style("stroke", function (d) {
                return "#000000";
            })
            .style("stroke-opacity", 1)
            .attr("stroke-width", 0.3);

        function minutesToNumber(minutes) {
            return ((1.666) * minutes) / 100; // 100/60 = 1.666
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
            if (room === "")
                return;
            if (rooms[room] == null) {
                rooms[room] = roomCounter;
                roomCounter++;
            }
            return rooms[room];
        }

        function setClassColorIndex(cname) {
            if (gridColorsIndex[cname] == null) {
                gridColorsIndex[cname] = gridColorCounter++;
            }
            return gridColorsIndex[cname];
        }

        function bottomHeightLimit(blockY, blockHeight) {
            if (blockY > height) {
                return 0;
            } else if (blockY + blockHeight > height) {
                return height - blockY;
            }
            return blockHeight;
        }

        function topHeightLimit(blockY, blockHeight) {
            if (blockY < 0) {
                if (blockHeight + blockY > 0) {
                    return blockHeight + blockY; // set y to zero and calc new block height
                }
                return 0; // Class is out of the range
            }
            return blockHeight;
        }

        function brArray(a) {
            var result = '';
            for (var i = 0; i < a.length; i++) {
                result += (a[i] + '<br>');
            }
            return result;
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
        $('#submit-ticket').click(function (e) {
            e.preventDefault();
            //var form = $(this);
            var postData = {event_id: $('#event_id').val(), message: $('#message').val()};
            var url = 'tickets/add-ticket'; //form.attr("action");

            $.ajax({
                url: url,
                type: "POST",
                data: postData,
                success: function (data, textStatus, jqXHR)
                {
                    $('#po-d3').hide();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var message = $.parseJSON(jqXHR.responseText);
                    alert(message.error);
                    // TODO:  bootstrap error message
                }
            });

            return false;
        });
    }

    function yearSelectionHandler(sched, d3Select, doInit, sched2) {
        if (sched != 'None' && sched != '') {
            spin.spin();
            body.append(spin.el);
        }

        var schType = 2;
        var url = vis_url + '/' + sched + '/' + schType;
        if (sched2 != undefined && sched2 != '') {
            schType = 3;
            url = vis_url + '/' + sched + '/' + schType + '/' + sched2;
        }

        $.ajax({
            dataType: "json",
            url: url,
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
                createChart(d3Select, data, doInit);
                spin.stop();
            },
            error: function (e) {
                spin.stop();
            }
        });
    }

    function createSelect(tag_prefix, label, multiple, classes) {
        var mult = "";
        if (multiple) {
            mult += 'multiple="multiple"';
        }
        return '<div id="' + tag_prefix + '-fg" class="form-group"><label id="' + tag_prefix + '-lbl" for="' + tag_prefix + '-sel">' + label + '</label><select id="' + tag_prefix + '-sel" ' + mult + ' class="vis-select ' + classes + '"></select></div>';
    }

    function createOption(key, value) {
        return '<option value="' + key + '">' + value + '</option>';
    }

    function setupForDiff() {
        $('#sched1-fg').show();
        $('#vis-go-button').show();
        $('#rooms-fg').hide();
        $('#prof-fg').hide();
        $('#class-fg').hide();
        $('#class-type-fg').hide();
        $('#sched-lbl').html('Schedule 1');
    }

    function setupForStandard() {
        $('#sched1-fg').hide();
        $('#vis-go-button').hide();
        $('#rooms-fg').show();
        $('#prof-fg').show();
        $('#class-fg').show();
        $('#class-type-fg').show();
        $('#sched-lbl').html('Schedule');
    }

    function render(sched) {
        $('footer').hide();
        $('.top-buffer').hide();
        $("#content").html('');

        d3.select('#content').style('min-width', '0px');
        d3.select('#content').style('min-height', '0px');

        $('#vis-menu').remove();
        $('#left-nav').append('<div id="vis-menu"></div>');

        var visMenu = $('#vis-menu');
        visMenu.hide();

        if (d3.select('#vis-wrapper').attr('data-auth-status') === '1') {
            visMenu.append(createSelect('sched-type', 'Type', false, null));
            $('#sched-type-sel').append('<option value="standard">Standard</option><option value="diff">Diff</option>');
        }

        visMenu.append(createSelect('sched', 'Schedule', false, null));
        visMenu.append(createSelect('sched1', 'Schedule 2', false, null));
        
        visMenu.append('<button id="vis-go-button" type="button" class="btn btn-default">Go!</button>');
        var goBtn = $('#vis-go-button');
        goBtn.hide();
        
        visMenu.append(createSelect('rooms', 'Room', true, 'd_select'));
        visMenu.append(createSelect('prof', 'Professor', true, 'd_select'));
        visMenu.append(createSelect('class', 'Class', true, 'd_select'));
        visMenu.append(createSelect('class-type', 'Class Type', true, 'd_select'));
        body = $('body');
        spin = new Spinner();

        spin.spin();
        body.append(spin.el);

        $.ajax({
            dataType: "json",
            url: vis_url + '/list',
            success: function (data) {
                var result = '';

                if (sched == undefined) {
                    for (var i = 0; i < data.length; i++) {
                        result += createOption(data[i]['id'], data[i]['name']);
                        //result += '<option value="' + data[i]['id'] + '">' + data[i]['name'] + '</option>';
                    }
                } else {
                    // Only add the single item
                    for (var i = 0; i < data.length; i++) {
                        if (data[i]['id'] === sched) {
                            result += createOption(data[i]['id'], data[i]['name']);
                        }
                    }
                }

                // Need to refresh this after adding
                $('#sched-sel').append(result);
                $('#sched1-sel').append(result);

                $('#sched-sel').multiselect('rebuild');
                $('#sched-sel').multiselect('refresh');
                $('#sched1-sel').multiselect('rebuild');
                $('#sched1-sel').multiselect('refresh');

                $('#sched1-fg').hide();

                $("#content").load("assets/vis/vis3Filt.html", function () {
                    yearSelectionHandler((sched || data[0]['id']), '#d3', true);
                    visMenu.show();
                    spin.stop();
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Request Error: " + textStatus + "; " + errorThrown);
            }
        });

        // Outside of function
        $('select').multiselect({
            maxHeight: 175,
            buttonWidth: '175px'
        });

        $('#sched-sel').multiselect('setOptions', {
            onChange: function (event) {
                // Don't trigger event when diff is selected
                if (!diffSelected) {
                    yearSelectionHandler($("#sched-sel").val(), '#d3', false);
                }
            }
        });

        goBtn.click(function () {
            yearSelectionHandler($("#sched-sel").val(), '#d3', false, $("#sched1-sel").val());
        });

        $('.d_select').multiselect('setOptions', {
            onChange: function (option, checked, select) {
                if (checked) {
                    d3.selectAll("." + $(option).val()).style("visibility", "visible");
                } else {
                    d3.selectAll("." + $(option).val()).style("visibility", "hidden");
                }
            }
        });

        $('#sched-type-sel').multiselect('setOptions', {
            onChange: function (event) {
                if ($("#sched-type-sel").val() == 'diff') {
                    diffSelected = true;
                    setupForDiff();
                } else {
                    diffSelected = false;
                    setupForStandard();
                }
            }
        });
    }

    return {
        render: render
    };

}());
