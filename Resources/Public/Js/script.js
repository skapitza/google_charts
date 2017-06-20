function initGraph() {
    $('.chart').each(function (index) {

        var chartnum = $(this).attr('data-charttype'),
                chartW = $(this).attr('data-width'),
                chartH = $(this).attr('data-height'),
                chartTitle = $(this).attr('data-title'),
                json = $(this).attr('data-json'),
                xaxis = $(this).attr('data-xaxis'),
                yaxis = $(this).attr('data-yaxis'),
                dataoptions,
                addoptions = $(this).attr('data-options');
        //json = $(this).attr('data-json');

        var sheetid = $(this).attr('data-sheet');
        var id = $(this).attr('id');

        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages': ['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {
            if (json != '') {
                json = JSON.parse(json);
                json = new google.visualization.DataTable(json['table']);
                jsonDraw(json);
            } else {
                var query = new google.visualization.Query("https://docs.google.com/spreadsheets/d/" + sheetid[0] + "/gviz/tq?headers=1");
                query.send(handleQueryResponse);
            }
        }

        var options = {
            'title': chartTitle,
            'width': 400,
            'height': 300,
            hAxis: {
                'title': ''
            },
            vAxis: {
                'title': yaxis
            }
        };

        if (chartW > 0)
            options.width = chartW;
        if (chartH > 0)
            options.height = chartH;

        function jsonDraw(json) {
            if (json.qg[0].c[0].v instanceof Date)
                options.hAxis.format = 'y';
            if (xaxis != '')
                options.hAxis.title = xaxis;
            else
                options.hAxis.title = json.pg[0].label;
            options.hAxis.gridlines = {count: json.qg.length};
            
            if (addoptions != '') {
                dataoptions = JSON.parse(addoptions);
                $.extend(true, options, dataoptions[0]);
            }
            var data = json;

            switch (chartnum) {
                case "1":
                    var chart = new google.visualization.LineChart(document.getElementById(id));
                    chart.draw(data, options);
                    break;
                case "2":
                    var chart = new google.visualization.PieChart(document.getElementById(id));
                    chart.draw(data, options);
                    break;
                case "3":
                    var chart = new google.visualization.ColumnChart(document.getElementById(id));
                    chart.draw(data, options);
                    break;
                default:
                    document.getElementById(id).innerHTML = "Kein Charttyp ausgewählt.";
            }
        }

        function handleQueryResponse(response) {
            if (response.J.qg[0].c[0].v instanceof Date)
                options.hAxis.format = 'y';
            options.hAxis.title = response.J.pg[0].label;
            var data = response.getDataTable();

            console.log(data);

            switch (chartnum) {
                case "1":
                    options.hAxis.gridlines = {count: response.J.qg.length};
                    var chart = new google.visualization.LineChart(document.getElementById(id));
                    chart.draw(data, options);
                    break;
                case "2":
                    var chart = new google.visualization.PieChart(document.getElementById(id));
                    chart.draw(data, options);
                    break;
                case "3":
                    options.hAxis.gridlines = {count: response.J.qg.length};
                    var chart = new google.visualization.ColumnChart(document.getElementById(id));
                    chart.draw(data, options);
                    break;
                default:
                    document.getElementById(id).innerHTML = "Kein Charttyp ausgewählt.";
            }
        }

    });
}

$(document).ready(function () {
    initGraph();
});