import helper from './helper'
import chart, { helpers } from 'chart.js'

(function($) { 
    "use strict";
        
    // Chart
    let constColors = ['pink', 'teal', 'blue', 'indigo', 'violet', 'purple', 'green', 'orange', 'brown', 'beige', 'yellow', 'black', 'slate', 'grey', 'red'];


    if ($('#inventories_by_type').length) {
        let data = JSON.parse(document.getElementById('inventories_by_type_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item) {
            labels.push(item.status);
            values.push(item.value);
            colors.push(item.color);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${item.color};"></div>
                <span class="truncate">${item.status}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#inventories_by_type_meta").html(html);
        let ctx = $('#inventories_by_type')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }

    if ($('#leads_by_status').length) {
        let data = JSON.parse(document.getElementById('leads_by_status_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item) {
            labels.push(item.status);
            values.push(item.value);
            colors.push(item.color);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${item.color};"></div>
                <span class="truncate">${item.status}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#leads_by_status_meta").html(html);
        let ctx = $('#leads_by_status')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }
    
    if ($('#leads_by_tags').length) {
        let data = JSON.parse(document.getElementById('leads_by_tags_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item) {
            labels.push(item.status);
            values.push(item.value);
            colors.push(item.color);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${item.color};"></div>
                <span class="truncate">${item.status}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#leads_by_tags_meta").html(html);
        let ctx = $('#leads_by_tags')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }
    
    if ($('#customer_gender_pie_chart').length) {
        let data = JSON.parse(document.getElementById('customer_gender_pie_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item) {
            labels.push(item.label);
            values.push(item.value);
            colors.push(item.color);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${item.color};"></div>
                <span class="truncate">${item.label}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#customer_gender_pie_chart_meta").html(html);
        let ctx = $('#customer_gender_pie_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }

    if ($('#customer_status_chart').length) {
        let data = JSON.parse(document.getElementById('customer_status_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item) {
            labels.push(item.label);
            values.push(item.value);
            colors.push(item.color);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${item.color};"></div>
                <span class="truncate">${item.label}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#customer_status_chart_meta").html(html);
        let ctx = $('#customer_status_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }

    if ($('#customer_tag_chart').length) {
        let data = JSON.parse(document.getElementById('customer_tag_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item) {
            labels.push(item.label);
            values.push(item.value);
            colors.push(item.color);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${item.color};"></div>
                <span class="truncate">${item.label}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#customer_tag_chart_data_meta").html(html);
        let ctx = $('#customer_tag_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }

    if ($('#lead_country_pie_chart').length) {
        let data = JSON.parse(document.getElementById('lead_country_pie_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item) {
            labels.push(item.label);
            values.push(item.value);
            colors.push(item.color);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${item.color};"></div>
                <span class="truncate">${item.label}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#lead_country_pie_chart_meta").html(html);
        let ctx = $('#lead_country_pie_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }

    if ($('#lead_city_pie_chart').length) {
        let data = JSON.parse(document.getElementById('lead_city_pie_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item) {
            labels.push(item.label);
            values.push(item.value);
            colors.push(item.color);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${item.color};"></div>
                <span class="truncate">${item.label}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#lead_city_pie_chart_meta").html(html);
        let ctx = $('#lead_city_pie_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }
    
    if ($('#lead_gender_pie_chart').length) {
        let data = JSON.parse(document.getElementById('lead_gender_pie_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item) {
            labels.push(item.label);
            values.push(item.value);
            colors.push(item.color);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${item.color};"></div>
                <span class="truncate">${item.label}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#lead_gender_pie_chart_meta").html(html);
        let ctx = $('#lead_gender_pie_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }

    if ($('#lead_tags_pie_chart').length) {
        let data = JSON.parse(document.getElementById('lead_tags_pie_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item) {
            labels.push(item.label);
            values.push(item.value);
            colors.push(item.color);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${item.color};"></div>
                <span class="truncate">${item.label}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#lead_tags_pie_chart_meta").html(html);
        let ctx = $('#lead_tags_pie_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }
    
    if ($('#lead_status_pie_chart').length) {
        let data = JSON.parse(document.getElementById('lead_status_pie_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item) {
            labels.push(item.label);
            values.push(item.value);
            colors.push(item.color);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${item.color};"></div>
                <span class="truncate">${item.label}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#lead_status_pie_chart_meta").html(html);
        let ctx = $('#lead_status_pie_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }

    
    if ($('#customer_top_countries_chart').length) {
        let data = JSON.parse(document.getElementById('customer_top_countries_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.counts_by_country.map(function(item, index) {
            labels.push(item.name);
            values.push(item.count);
            colors.push(constColors[index % 16]);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${constColors[index]};"></div>
                <span class="truncate">${item.name}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.count}</span> 
            </div>`
        });
        $("#customer_top_countries_chart_meta").html(html);
        let ctx = $('#customer_top_countries_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }

    if ($('#customer_top_cities_chart').length) {
        let data = JSON.parse(document.getElementById('customer_top_cities_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.counts_by_city.map(function(item, index) {
            labels.push(item.name);
            values.push(item.count);
            colors.push(constColors[index % 16]);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${constColors[index]};"></div>
                <span class="truncate">${item.name}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.count}</span> 
            </div>`
        });
        $("#customer_top_cities_chart_meta").html(html);
        let ctx = $('#customer_top_cities_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }

    
    if ($('#inventory_mechanical_condition_chart').length) {
        let data = JSON.parse(document.getElementById('inventory_mechanical_condition_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item, index) {
            labels.push(item.label);
            values.push(item.value);
            colors.push(constColors[(index + 7) % 15]);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${constColors[(index + 7) % 15]};"></div>
                <span class="truncate">${item.label}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#inventory_mechanical_condition_chart_meta").html(html);
        let ctx = $('#inventory_mechanical_condition_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }

    if ($('#inventory_body_condition_chart').length) {
        let data = JSON.parse(document.getElementById('inventory_body_condition_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item, index) {
            labels.push(item.label);
            values.push(item.value);
            colors.push(constColors[(index + 4) % 15]);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${constColors[(index + 4) % 15]};"></div>
                <span class="truncate">${item.label}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#inventory_body_condition_chart_meta").html(html);
        let ctx = $('#inventory_body_condition_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }

    if ($('#inventory_status_chart').length) {
        let data = JSON.parse(document.getElementById('inventory_status_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item, index) {
            labels.push(item.label);
            values.push(item.value);
            colors.push(constColors[(index + 3) % 15]);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${constColors[(index + 3) % 15]};"></div>
                <span class="truncate">${item.label}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#inventory_status_chart_meta").html(html);
        let ctx = $('#inventory_status_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }
    
    if ($('#inventory_brands_chart').length) {
        let data = JSON.parse(document.getElementById('inventory_brands_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.counts_by_brand.map(function(item, index) {
            labels.push(item.name);
            values.push(item.count);
            colors.push(constColors[(index + 2) % 15]);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${constColors[(index + 2) % 15]};"></div>
                <span class="truncate">${item.name}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.count}</span> 
            </div>`
        });
        $("#inventory_brands_chart_meta").html(html);
        let ctx = $('#inventory_brands_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }
    
    if ($('#inventory_photos_chart').length) {
        let data = JSON.parse(document.getElementById('inventory_photos_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item, index) {
            labels.push(item.name);
            values.push(item.value);
            colors.push(constColors[index % 15]);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${constColors[index % 15]};"></div>
                <span class="truncate">${item.name}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#inventory_photos_chart_meta").html(html);
        let ctx = $('#inventory_photos_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }
    
    if ($('#inventory_stockage_chart').length) {
        let data = JSON.parse(document.getElementById('inventory_stockage_chart_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item, index) {
            labels.push(item.name);
            values.push(item.value);
            colors.push(constColors[(index + 1) % 15]);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${constColors[(index + 1) % 15]};"></div>
                <span class="truncate">${item.name}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#inventory_stockage_chart_meta").html(html);
        let ctx = $('#inventory_stockage_chart')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }
    
    if ($('#inventories_by_make').length) {
        let data = JSON.parse(document.getElementById('inventories_by_make_data').innerHTML);
        let labels = [];
        let values = [];
        let colors = [];
        let html = "";
        data.map(function(item, index) {
            labels.push(item.make_name);
            values.push(item.value);
            colors.push(constColors[(index - 1) % 15]);
            
            html += `<div class="flex items-center">
                <div class="w-2 h-2 rounded-full mr-3" style="background-color: ${constColors[(index - 1) % 15]};"></div>
                <span class="truncate">${item.make_name}</span> 
                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                <span class="font-medium xl:ml-auto">${item.value}</span> 
            </div>`
        });
        $("#inventories_by_make_meta").html(html);
        let ctx = $('#inventories_by_make')[0].getContext('2d')
        let myPieChart = new chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 5,
                    borderColor: "#fff"
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        })
    }

})($)