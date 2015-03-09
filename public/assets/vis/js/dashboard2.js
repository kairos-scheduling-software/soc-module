var dashboard2 = (function () {

    "use strict";

    var chart1;

    function createChart(selector, dataset) {

    }

    function render() {

        var html = '';
        $("#content").html(html);

        chart1 = createChart(null, null);
    }

    return {
        render: render
    }

}());
