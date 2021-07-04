/* ------------------------------------------------------------------------------
 *
 *  # Statistics widgets
 *
 *  Demo JS code for widgets_stats.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var InventoryDashboard = function () {
    // Donut with legend
    _dashboardChart = function () {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }
        var top_makes_chart_element = document.getElementById('top_makes_chart');
        var last_added_chart_element = document.getElementById('last_added_chart');
        var location_chart_element = document.getElementById('location_chart');
        var published_photos_chart_element = document.getElementById('published_photos_chart');
        var published_stok_ages_chart_element = document.getElementById('published_stok_ages_chart');


        if (top_makes_chart_element) {
            var top_makes_chart = echarts.init(top_makes_chart_element)
            // console.log(JSON.parse(document.getElementById('brand_chart_details').innerHTML).counts_by_brand, "+++++++++")
            //
            // Chart config
            //

            // Top text label
            var labelTop = {
                showHtml: true,
                position: 'center',
                formatter: '{b}\n',
                fontSize: 15,
                lineHeight: 50,
                rich: {
                    a: {}
                }
            };

            // Background item style
            var backStyle = {
                color: '#eee',
                emphasis: {
                    color: '#eee'
                }
            };

            // Bottom text label
            var labelBottom = {
                color: '#333',
                show: true,
                position: 'center',
                formatter: function (params) {
                    return '\n\n' + (100 - params.value).toFixed(2) + '%'
                },
                fontWeight: 500,
                lineHeight: 35,
                rich: {
                    a: {}
                },
                emphasis: {
                    color: '#333'
                }
            };

            // Set inner and outer radius
            var radius = [];
            var radius_array = [
                [52, 65],
                [95, 110],
                [85, 97],
                [75, 87],
                [65, 77],
            ]
            // Options
            var brand_length = JSON.parse(document.getElementById('brand_chart_details').innerHTML).counts_by_brand.length
            // if(brand_length > 5 ){
            //     brand_length = brand_length-5
            // }
            var center_array = [
                [50],
                [30, 70],
                [20, 50, 80],
                [10, 40, 60, 90],
                [10, 30, 50, 70, 90],
                [10, 30, 50, 70, 90, 50],
                [10, 30, 50, 70, 90, 30, 70],
                [10, 30, 50, 70, 90, 20, 50, 80],
                [10, 30, 50, 70, 90, 10, 40, 60, 90],
                [10, 30, 50, 70, 90, 10, 30, 50, 70, 90],
            ]
            top_makes_chart.setOption({

                // Colors
                color: [
                    '#2ec7c9', '#b6a2de', '#5ab1ef', '#ffb980', '#d87a80',
                    '#8d98b3', '#e5cf0d', '#97b552', '#95706d', '#dc69aa',
                    '#07a2a4', '#9a7fd1', '#588dd5', '#f5994e', '#c05050',
                    '#59678c', '#c9ab00', '#7eb00a', '#6f5553', '#c14089'
                ],

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Add title
                title: {
                    text: 'Top Makes(Brand)',
                    subtext: 'Most popular makes in your inventory',
                    left: 'center',
                    textStyle: {
                        fontSize: 17,
                        fontWeight: 500
                    },
                    subtextStyle: {
                        fontSize: 12
                    }
                },

                // Add legend
                legend: {
                    bottom: 0,
                    left: 'center',
                    data: ['GMaps', 'Facebook', 'Youtube', 'Google+', 'Weixin', 'Twitter', 'Skype', 'Messenger', 'Whatsapp', 'Instagram'],
                    itemHeight: 8,
                    itemWidth: 8,
                    selectedMode: false
                },

                // Add series
                series: JSON.parse(document.getElementById('brand_chart_details').innerHTML).counts_by_brand.map((count_brand, index) => {
                    console.log(count_brand, brand_length)
                    return {
                        type: 'pie',
                        center: [center_array[brand_length - 1][index] + '%', index < 5 ? '33%' : '73%'],

                        radius: radius_array[brand_length % 5],
                        hoverAnimation: false,
                        data: [{
                                name: 'other',
                                value: 100 - count_brand.percent,
                                label: labelBottom,
                                itemStyle: backStyle
                            },
                            {
                                name: count_brand.name,
                                value: count_brand.percent.toFixed(2),
                                label: labelTop
                            }
                        ]
                    }
                })
            });

        }
        if (last_added_chart_element) {
            // Initialize chart
            var last_added_chart = echarts.init(last_added_chart_element);


            //
            // Chart config
            //

            // Options
            last_added_chart.setOption({

                // Define colors
                color: ['#2ec7c9', '#b6a2de', '#5ab1ef', '#ffb980', '#d87a80'],

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Chart animation duration
                animationDuration: 750,

                // Setup grid
                grid: {
                    left: 0,
                    right: 10,
                    top: 35,
                    bottom: 0,
                    containLabel: true
                },

                // Add legend
                legend: {
                    data: JSON.parse(document.getElementById('last_added_chart_data').innerHTML).legend,
                    itemHeight: 8,
                    itemGap: 20
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(0,0,0,0.75)',
                    padding: [10, 15],
                    textStyle: {
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    },
                    axisPointer: {
                        type: 'shadow',
                        shadowStyle: {
                            color: 'rgba(0,0,0,0.025)'
                        }
                    }
                },

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: JSON.parse(document.getElementById('last_added_chart_data').innerHTML).dates, //['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    axisLabel: {
                        color: '#333'
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#999'
                        }
                    },
                    splitLine: {
                        show: true,
                        lineStyle: {
                            color: '#eee',
                            type: 'dashed'
                        }
                    }
                }],

                // Vertical axis
                yAxis: [{
                    type: 'value',
                    axisLabel: {
                        color: '#333'
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#999'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: '#eee'
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
                        }
                    }
                }],

                // Add series
                series: JSON.parse(document.getElementById('last_added_chart_data').innerHTML).series
            });
        }
        // Nested
        if (location_chart_element) {

            // Initialize chart
            var location_chart = echarts.init(location_chart_element);


            //
            // Chart config
            //

            // Options
            location_chart.setOption({

                // Colors
                color: [
                    '#2ec7c9', '#b6a2de', '#5ab1ef', '#ffb980', '#d87a80',
                    '#8d98b3', '#e5cf0d', '#97b552', '#95706d', '#dc69aa',
                    '#07a2a4', '#9a7fd1', '#588dd5', '#f5994e', '#c05050',
                    '#59678c', '#c9ab00', '#7eb00a', '#6f5553', '#c14089'
                ],

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    backgroundColor: 'rgba(0,0,0,0.75)',
                    padding: [10, 15],
                    textStyle: {
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    },
                    formatter: '{a} <br/>{b}: {c} ({d}%)'
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    top: 'center',
                    left: 0,
                    data: ['Italy', 'Spain', 'Austria', 'Germany', 'Poland', 'Denmark', 'Hungary', 'Portugal', 'France', 'Netherlands'],
                    itemHeight: 8,
                    itemWidth: 8
                },

                // Add series
                series: [

                    // Inner
                    {
                        name: 'Countries',
                        type: 'pie',
                        selectedMode: 'single',
                        radius: [0, '50%'],
                        itemStyle: {
                            normal: {
                                borderWidth: 1,
                                borderColor: '#fff',
                                label: {
                                    position: 'inner'
                                },
                                labelLine: {
                                    show: false
                                }
                            }
                        },
                        data: JSON.parse(document.getElementById('location_chart_data').innerHTML).countries_series
                    },

                    // Outer
                    {
                        name: 'Cities',
                        type: 'pie',
                        radius: ['60%', '85%'],
                        itemStyle: {
                            normal: {
                                borderWidth: 1,
                                borderColor: '#fff'
                            }
                        },
                        data: JSON.parse(document.getElementById('location_chart_data').innerHTML).cities_series
                    }
                ]
            });
        }

        if (published_photos_chart_element) {
            // Initialize chart
            var published_photos_chart = echarts.init(published_photos_chart_element);


            //
            // Chart config
            //

            // Options
            published_photos_chart.setOption({

                // Colors
                color: [
                    'red', 'orange', 'yellow', 'green'
                ],

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Add title
                title: {
                    text: 'Photos',
                    subtext: 'Published Inventories by Photos',
                    left: 'center',
                    textStyle: {
                        fontSize: 17,
                        fontWeight: 500
                    },
                    subtextStyle: {
                        fontSize: 12
                    }
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    backgroundColor: 'rgba(0,0,0,0.75)',
                    padding: [10, 15],
                    textStyle: {
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    },
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    top: 'center',
                    left: 0,
                    data: ['0 Photos', 'Less than 5 Photos', 'Less than 10 Photos', 'More than 10 Photos'],
                    itemHeight: 8,
                    itemWidth: 8
                },

                // Add series
                series: [{
                    name: 'Photos',
                    type: 'pie',
                    radius: '70%',
                    center: ['50%', '57.5%'],
                    itemStyle: {
                        normal: {
                            borderWidth: 1,
                            borderColor: '#fff'
                        }
                    },
                    data: JSON.parse(document.getElementById('inventories_with_photos_chart').innerHTML)
                }]
            });
        }
        if (published_stok_ages_chart_element) {
            // Initialize chart
            var published_stok_ages_chart = echarts.init(published_stok_ages_chart_element);


            //
            // Chart config
            //

            // Options
            published_stok_ages_chart.setOption({

                // Colors
                color: [
                    'green', 'yellow', 'gold', 'orange', 'orangered', 'red'
                ],

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Add title
                title: {
                    text: 'Stock Age',
                    subtext: 'Published Inventories by Stock Age',
                    left: 'center',
                    textStyle: {
                        fontSize: 17,
                        fontWeight: 500
                    },
                    subtextStyle: {
                        fontSize: 12
                    }
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    backgroundColor: 'rgba(0,0,0,0.75)',
                    padding: [10, 15],
                    textStyle: {
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    },
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    top: 'center',
                    left: 0,
                    data: ['Less than 7 Days', '7 Days - 30 Days', '30 Days - 60 Days', '60 Days - 90 Days', '90 Days - 120 Days', 'More than 120 Days'],
                    itemHeight: 8,
                    itemWidth: 8
                },

                // Add series
                series: [{
                    name: 'Stock Age',
                    type: 'pie',
                    radius: '70%',
                    center: ['50%', '57.5%'],
                    itemStyle: {
                        normal: {
                            borderWidth: 1,
                            borderColor: '#fff'
                        }
                    },
                    data: JSON.parse(document.getElementById('stock_age_chart_data').innerHTML)
                }]
            });
        }
        //
        // Resize charts
        //
        // Resize function
        var triggerChartResize = function () {
            top_makes_chart_element && top_makes_chart.resize();
            last_added_chart_element && last_added_chart.resize();
            location_chart_element && location_chart.resize();
        };
        // On sidebar width change
        $(document).on('click', '.sidebar-control', function () {
            setTimeout(function () {
                triggerChartResize();
            }, 0);
        });

        // On window resize
        var resizeCharts;
        window.onresize = function () {
            clearTimeout(resizeCharts);
            resizeCharts = setTimeout(function () {
                triggerChartResize();
            }, 200);
        };

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
        _animatedPieWithLegend("#mechanical_condition_pie_chart", 120, JSON.parse(document.getElementById('mechanical_condition_chart_data').innerHTML));
        _animatedPieWithLegend("#body_condition_pie_chart", 120, JSON.parse(document.getElementById('body_condition_chart_data').innerHTML));
        _animatedPieWithLegend("#inventory_status_pie_chart", 120, JSON.parse(document.getElementById('inventory_status_chart_data').innerHTML));
    };

    return {

        init: function () {
            _dashboardChart()
        }
    }
}();


// Initialize module
// ------------------------------

// When content loaded
document.addEventListener('DOMContentLoaded', function () {
    InventoryDashboard.init();
});
