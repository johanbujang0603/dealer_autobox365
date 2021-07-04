var LeadDashboard = function () {
    _dashboardChart = function () {
        var center_array = [
            [50],
            [30, 70],
            [20, 50, 80],
            [10, 40, 60, 90],
            [10, 30, 50, 70, 90],
        ]
        var radius_array = [
            [95, 110],
            [75, 87],
            [65, 77],
            [52, 65],
            [42, 52],
        ]
        // var top_countries_chart_element = document.getElementById('top_countries_chart');
        // var top_cities_chart_element = document.getElementById('top_cities_chart');
        //
        // if (top_countries_chart_element) {
        //     var top_countries_chart = echarts.init(top_countries_chart_element)
        //     var labelTop = {
        //         showHtml: true,
        //         position: 'center',
        //         formatter: '{b}\n',
        //         fontSize: 15,
        //         lineHeight: 50,
        //         rich: {
        //             a: {}
        //         }
        //     };
        //
        //     // Background item style
        //     var backStyle = {
        //         color: '#eee',
        //         emphasis: {
        //             color: '#eee'
        //         }
        //     };
        //
        //     // Bottom text label
        //     var labelBottom = {
        //         color: '#333',
        //         show: true,
        //         position: 'center',
        //         formatter: function (params) {
        //             return '\n\n' + (100 - params.value).toFixed(2) + '%'
        //         },
        //         fontWeight: 500,
        //         lineHeight: 35,
        //         rich: {
        //             a: {}
        //         },
        //         emphasis: {
        //             color: '#333'
        //         }
        //     };
        //
        //     // Set inner and outer radius
        //     var radius = [];
        //
        //     // Options
        //     var brand_length = JSON.parse(document.getElementById('country_chart_data').innerHTML).counts_by_country.length
        //
        //     top_countries_chart.setOption({
        //
        //         // Colors
        //         color: [
        //             '#2ec7c9', '#b6a2de', '#5ab1ef', '#ffb980', '#d87a80',
        //             '#8d98b3', '#e5cf0d', '#97b552', '#95706d', '#dc69aa',
        //             '#07a2a4', '#9a7fd1', '#588dd5', '#f5994e', '#c05050',
        //             '#59678c', '#c9ab00', '#7eb00a', '#6f5553', '#c14089'
        //         ],
        //
        //         // Global text styles
        //         textStyle: {
        //             fontFamily: 'Roboto, Arial, Verdana, sans-serif',
        //             fontSize: 13
        //         },
        //
        //         // Add title
        //         title: {
        //             text: 'Top Countries',
        //             subtext: 'from global web index',
        //             left: 'center',
        //             textStyle: {
        //                 fontSize: 17,
        //                 fontWeight: 500
        //             },
        //             subtextStyle: {
        //                 fontSize: 12
        //             }
        //         },
        //
        //         // Add legend
        //         legend: {
        //             bottom: 0,
        //             left: 'center',
        //             data: ['GMaps', 'Facebook', 'Youtube', 'Google+', 'Weixin', 'Twitter', 'Skype', 'Messenger', 'Whatsapp', 'Instagram'],
        //             itemHeight: 8,
        //             itemWidth: 8,
        //             selectedMode: false
        //         },
        //
        //         // Add series
        //         series: JSON.parse(document.getElementById('country_chart_data').innerHTML).counts_by_country.map((count_country, index) => {
        //             console.log(count_country)
        //             return {
        //                 type: 'pie',
        //                 center: [center_array[brand_length - 1][index] + '%', index < 5 ? '43%' : '73%'],
        //
        //                 radius: radius_array[brand_length - 1],
        //                 hoverAnimation: false,
        //                 data: [{
        //                         name: 'other',
        //                         value: 100 - count_country.percent,
        //                         label: labelBottom,
        //                         itemStyle: backStyle
        //                     },
        //                     {
        //                         name: count_country.name,
        //                         value: count_country.percent.toFixed(2),
        //                         label: labelTop
        //                     }
        //                 ]
        //             }
        //         })
        //     });
        // }
        // if (top_cities_chart_element) {
        //     var top_cities_chart = echarts.init(top_cities_chart_element)
        //     var labelTop = {
        //         showHtml: true,
        //         position: 'center',
        //         formatter: '{b}\n',
        //         fontSize: 15,
        //         lineHeight: 50,
        //         rich: {
        //             a: {}
        //         }
        //     };
        //
        //     // Background item style
        //     var backStyle = {
        //         color: '#eee',
        //         emphasis: {
        //             color: '#eee'
        //         }
        //     };
        //
        //     // Bottom text label
        //     var labelBottom = {
        //         color: '#333',
        //         show: true,
        //         position: 'center',
        //         formatter: function (params) {
        //             return '\n\n' + (100 - params.value).toFixed(2) + '%'
        //         },
        //         fontWeight: 500,
        //         lineHeight: 35,
        //         rich: {
        //             a: {}
        //         },
        //         emphasis: {
        //             color: '#333'
        //         }
        //     };
        //
        //     // Set inner and outer radius
        //     var radius = [];
        //
        //     // Options
        //     var brand_length = JSON.parse(document.getElementById('cities_chart_data').innerHTML).counts_by_city.length
        //
        //     top_cities_chart.setOption({
        //
        //         // Colors
        //         color: [
        //             '#2ec7c9', '#b6a2de', '#5ab1ef', '#ffb980', '#d87a80',
        //             '#8d98b3', '#e5cf0d', '#97b552', '#95706d', '#dc69aa',
        //             '#07a2a4', '#9a7fd1', '#588dd5', '#f5994e', '#c05050',
        //             '#59678c', '#c9ab00', '#7eb00a', '#6f5553', '#c14089'
        //         ],
        //
        //         // Global text styles
        //         textStyle: {
        //             fontFamily: 'Roboto, Arial, Verdana, sans-serif',
        //             fontSize: 13
        //         },
        //
        //         // Add title
        //         title: {
        //             text: 'Top Cities',
        //             subtext: 'from global web index',
        //             left: 'center',
        //             textStyle: {
        //                 fontSize: 17,
        //                 fontWeight: 500
        //             },
        //             subtextStyle: {
        //                 fontSize: 12
        //             }
        //         },
        //
        //         // Add legend
        //         legend: {
        //             bottom: 0,
        //             left: 'center',
        //             data: ['GMaps', 'Facebook', 'Youtube', 'Google+', 'Weixin', 'Twitter', 'Skype', 'Messenger', 'Whatsapp', 'Instagram'],
        //             itemHeight: 8,
        //             itemWidth: 8,
        //             selectedMode: false
        //         },
        //
        //         // Add series
        //         series: JSON.parse(document.getElementById('cities_chart_data').innerHTML).counts_by_city.map((count_city, index) => {
        //             console.log(count_city)
        //             return {
        //                 type: 'pie',
        //                 center: [center_array[brand_length - 1][index] + '%', index < 5 ? '43%' : '73%'],
        //
        //                 radius: radius_array[brand_length - 1],
        //                 hoverAnimation: false,
        //                 data: [{
        //                         name: 'other',
        //                         value: 100 - count_city.percent,
        //                         label: labelBottom,
        //                         itemStyle: backStyle
        //                     },
        //                     {
        //                         name: count_city.name,
        //                         value: count_city.percent.toFixed(2),
        //                         label: labelTop
        //                     }
        //                 ]
        //             }
        //         })
        //     });
        // }
        // Pie with legend
        var _animatedPieWithLegend = function (element, size, data) {
            if (typeof d3 == 'undefined') {
                console.warn('Warning - d3.min.js is not loaded.');
                return;
            }

            // Initialize chart only if element exsists in the DOM
            if (element) {

                // Add data set

                // Main variables
                var d3Container = d3.select(element),
                    distance = 2, // reserve 2px space for mouseover arc moving
                    radius = (size / 2) - distance,
                    sum = d3.sum(data, function (d) {
                        return d.value;
                    });


                // Create chart
                // ------------------------------

                // Add svg element
                var container = d3Container.append("svg");

                // Add SVG group
                var svg = container
                    .attr("width", size)
                    .attr("height", size)
                    .append("g")
                    .attr("transform", "translate(" + (size / 2) + "," + (size / 2) + ")");


                // Construct chart layout
                // ------------------------------

                // Pie
                var pie = d3.layout.pie()
                    .sort(null)
                    .startAngle(Math.PI)
                    .endAngle(3 * Math.PI)
                    .value(function (d) {
                        return d.value;
                    });

                // Arc
                var arc = d3.svg.arc()
                    .outerRadius(radius);


                //
                // Append chart elements
                //

                // Group chart elements
                var arcGroup = svg.selectAll(".d3-arc")
                    .data(pie(data))
                    .enter()
                    .append("g")
                    .attr("class", "d3-arc")
                    .style({
                        'stroke': '#fff',
                        'stroke-width': 2,
                        'cursor': 'pointer'
                    });

                // Append path
                var arcPath = arcGroup
                    .append("path")
                    .style("fill", function (d) {
                        return d.data.color;
                    });


                // Add interactions
                arcPath
                    .on('mouseover', function (d, i) {

                        // Transition on mouseover
                        d3.select(this)
                            .transition()
                            .duration(500)
                            .ease('elastic')
                            .attr('transform', function (d) {
                                d.midAngle = ((d.endAngle - d.startAngle) / 2) + d.startAngle;
                                var x = Math.sin(d.midAngle) * distance;
                                var y = -Math.cos(d.midAngle) * distance;
                                return 'translate(' + x + ',' + y + ')';
                            });

                        // Animate legend
                        $(element + ' [data-slice]').css({
                            'opacity': 0.3,
                            'transition': 'all ease-in-out 0.15s'
                        });
                        $(element + ' [data-slice=' + i + ']').css({
                            'opacity': 1
                        });
                    })
                    .on('mouseout', function (d, i) {

                        // Mouseout transition
                        d3.select(this)
                            .transition()
                            .duration(500)
                            .ease('bounce')
                            .attr('transform', 'translate(0,0)');

                        // Revert legend animation
                        $(element + ' [data-slice]').css('opacity', 1);
                    });

                // Animate chart on load
                arcPath
                    .transition()
                    .delay(function (d, i) {
                        return i * 500;
                    })
                    .duration(500)
                    .attrTween("d", function (d) {
                        var interpolate = d3.interpolate(d.startAngle, d.endAngle);
                        return function (t) {
                            d.endAngle = interpolate(t);
                            return arc(d);
                        };
                    });


                //
                // Append counter
                //

                // Append element
                d3Container
                    .append('h2')
                    .attr('class', 'pt-1 mt-2 mb-1 font-weight-semibold');

                // Animate counter
                d3Container.select('h2')
                    .transition()
                    .duration(1500)
                    .tween("text", function (d) {
                        var i = d3.interpolate(this.textContent, sum);

                        return function (t) {
                            this.textContent = d3.format(",d")(Math.round(i(t)));
                        };
                    });


                //
                // Append legend
                //

                // Add element
                var legend = d3.select(element)
                    .append('ul')
                    .attr('class', 'chart-widget-legend')
                    .selectAll('li').data(pie(data))
                    .enter().append('li')
                    .attr('data-slice', function (d, i) {
                        return i;
                    })
                    .attr('style', function (d, i) {
                        return 'border-bottom: 2px solid ' + d.data.color;
                    })
                    .text(function (d, i) {
                        return d.data.label + ': ';
                    });

                // Add value
                legend.append('span')
                    .text(function (d, i) {
                        return d.data.value;
                    });
            }
        };
        _animatedPieWithLegend("#gender_pie_chart", 120, JSON.parse(document.getElementById('gender_chart_data').innerHTML));
        _animatedPieWithLegend("#tag_pie_chart", 120, JSON.parse(document.getElementById('tag_chart_data').innerHTML));
        _animatedPieWithLegend("#status_pie_chart", 120, JSON.parse(document.getElementById('status_chart_data').innerHTML));
        _animatedPieWithLegend("#top_cities_chart", 120, JSON.parse(document.getElementById('cities_chart_data').innerHTML));
        _animatedPieWithLegend("#top_countries_chart", 120, JSON.parse(document.getElementById('country_chart_data').innerHTML));

    }
    return {
        init: function () {
            _dashboardChart()
        }
    }
}();

// When content loaded
document.addEventListener('DOMContentLoaded', function () {
    LeadDashboard.init();
});
