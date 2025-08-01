function formatMontant(montant) {
    var formattedMontant = montant.toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' });
    return formattedMontant.replace("XOF", "F CFA");
}

$(document).ready(function() {
    function invoiceByDay() {
        // Populate Data in Edit Modal Form
        $.ajax({
            type: "GET",
            url: baseUrl + "/home/invoiceByDay",
            success: function(data) {
                // Récupérez les montants pour la semaine actuelle et la semaine passée
                var currentWeekData = data.current; // Montants de la semaine actuelle
                var lastWeekData = data.last; // Montants de la semaine passée

                //Mises à jour des données graphique
                var e = ["#727cf5", "#0acf97", "#fa5c7c", "#ffbc00"];
                var r = {
                    chart: {
                        height: 364,
                        type: "line",
                        dropShadow: {
                            enabled: !0,
                            opacity: .2,
                            blur: 7,
                            left: -7,
                            top: 7
                        }
                    },
                    dataLabels: {
                        enabled: !1
                    },
                    stroke: {
                        curve: "smooth",
                        width: 4
                    },
                    series: [{
                            name: "Current Week",
                            data: Object.values(currentWeekData) // Utilisez les montants de la semaine actuelle ici
                        },
                        {
                            name: "Previous Week",
                            data: Object.values(lastWeekData) // Utilisez les montants de la semaine passée ici
                        }
                    ],
                    colors: e,
                    zoom: { enabled: !1 },
                    legend: { show: !1 },
                    xaxis: {
                        type: "string",
                        categories: ["Lun", "Mar", "Mer", "Jed", "Ven", "Sam", "Dim"],
                        tooltip: { enabled: !1 },
                        axisBorder: { show: !1 }
                    },
                    yaxis: {
                        labels: {
                            formatter: function(e) { return e / 1000 + "k" },
                            offsetX: -15
                        }
                    }
                };

                new ApexCharts(document.querySelector("#revenue-chart-test"), r).render();

                console.log(data);
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    }

    function invoicePaid() {
        // Populate Data in Edit Modal Form
        $.ajax({
            type: "GET",
            url: baseUrl + "/home/testOrderByStatus",
            success: function(data) {
                // Récupérez le nbr de factures de vente et d'avoir payées ou pas
                var invoicePaid = data.invoicePaid;
                var invoiceNoPaid = data.invoiceNoPaid;
                var refundPaid = data.refundPaid;
                var refundNoPaid = data.refundNoPaid;
                console.log(data);
                document.getElementById('invoicePaid').textContent = formatMontant(data.invoiceTotalPaid)
                document.getElementById('invoiceNoPaid').textContent = formatMontant(data.invoiceTotalNoPaid)
                document.getElementById('refundPaid').textContent = formatMontant(data.refundTotalPaid)
                document.getElementById('refundNoPaid').textContent = formatMontant(data.refundTotalNoPaid)


                //Mises à jour des données graphique
                e = ["#0acf97", "#ffbc00", "#727cf5", "#fa5c7c"];
                r = {
                    chart: { height: 208, type: "donut" },
                    legend: { show: !1 },
                    stroke: { colors: ["transparent"] },
                    series: [invoicePaid, invoiceNoPaid, refundPaid, refundNoPaid],
                    labels: ["Facture de vente payées", "Facture de vente non payées", "Facture d'avoir payées", "Facture d'avoir non payées"],
                    colors: e,
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: { width: 200 },
                            legend: { position: "bottom" }
                        }
                    }]
                };
                new ApexCharts(document.querySelector("#average-sales-test"), r).render()

                console.log(data);
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    }
    invoiceByDay();
    invoicePaid();
})



! function(o) {
    "use strict";

    function e() {
        this.$body = o("body"),
            this.charts = []
    }
    e.prototype.initCharts = function() {
            window.Apex = {
                chart: {
                    parentHeightOffset: 0,
                    toolbar: { show: !1 }
                },
                grid: {
                    padding: {
                        left: 0,
                        right: 0
                    }
                },
                colors: ["#727cf5", "#0acf97", "#fa5c7c", "#ffbc00"]
            };
            var e = ["#727cf5", "#0acf97", "#fa5c7c", "#ffbc00"],

                t = o("#revenue-chart").data("colors");

            t && (e = t.split(","));

            var r = {
                chart: {
                    height: 364,
                    type: "line",
                    dropShadow: {
                        enabled: !0,
                        opacity: .2,
                        blur: 7,
                        left: -7,
                        top: 7
                    }
                },
                dataLabels: {
                    enabled: !1
                },
                stroke: {
                    curve: "smooth",
                    width: 4
                },
                series: [{
                        name: "Current Week",
                        data: [10, 20, 15, 25, 20, 30, 20]
                    },
                    {
                        name: "Previous Week",
                        data: [0, 15, 10, 30, 15, 35, 25]
                    }
                ],
                colors: e,
                zoom: { enabled: !1 },
                legend: { show: !1 },
                xaxis: {
                    type: "string",
                    categories: ["Lun", "Mar", "Mer", "Jed", "Ven", "Sam", "Dim"],
                    tooltip: { enabled: !1 },
                    axisBorder: { show: !1 }
                },
                yaxis: {
                    labels: {
                        formatter: function(e) { return e + "k" },
                        offsetX: -15
                    }
                }
            };

            new ApexCharts(document.querySelector("#revenue-chart"), r).render();

            e = ["#727cf5", "#e3eaef"];

            (t = o("#high-performing-product").data("colors")) && (e = t.split(","));
            r = {
                chart: {
                    height: 257,
                    type: "bar",
                    stacked: !0
                },
                plotOptions: {
                    bar: { horizontal: !1, columnWidth: "20%" }
                },
                dataLabels: { enabled: !1 },
                stroke: {
                    show: !0,
                    width: 2,
                    colors: ["transparent"]
                },
                series: [{
                        name: "Actual",
                        data: [65, 59, 80, 81, 56, 89, 40, 32, 65, 59, 80, 81]
                    },
                    {
                        name: "Projection",
                        data: [89, 40, 32, 65, 59, 80, 81, 56, 89, 40, 65, 59]
                    }
                ],
                zoom: { enabled: !1 },
                legend: { show: !1 },
                colors: e,
                xaxis: {
                    categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    axisBorder: { show: !1 }
                },
                yaxis: {
                    labels: { formatter: function(e) { return e + "k" }, offsetX: -15 }
                },
                fill: { opacity: 1 },
                tooltip: {
                    y: {
                        formatter: function(e) { return "$" + e + "k" }
                    }
                }
            };
            new ApexCharts(document.querySelector("#high-performing-product"), r).render();
            e = ["#727cf5", "#0acf97", "#fa5c7c", "#ffbc00"];
            (t = o("#average-sales").data("colors")) && (e = t.split(","));
            r = {
                chart: { height: 208, type: "donut" },
                legend: { show: !1 },
                stroke: { colors: ["transparent"] },
                series: [44, 55, 41, 17],
                labels: ["Direct", "Affilliate", "Sponsored", "E-mail"],
                colors: e,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: { width: 200 },
                        legend: { position: "bottom" }
                    }
                }]
            };
            new ApexCharts(document.querySelector("#average-sales"), r).render()
        },
        e.prototype.initMaps = function() {
            0 < o("#world-map-markers").length && o("#world-map-markers").vectorMap({
                map: "world_mill_en",
                normalizeFunction: "polynomial",
                hoverOpacity: .7,
                hoverColor: !1,
                regionStyle: {
                    initial: {
                        fill: "#e3eaef"
                    }
                },
                markerStyle: {
                    initial: {
                        r: 9,
                        fill: "#727cf5",
                        "fill-opacity": .9,
                        stroke: "#fff",
                        "stroke-width": 7,
                        "stroke-opacity": .4
                    },
                    hover: {
                        stroke: "#fff",
                        "fill-opacity": 1,
                        "stroke-width": 1.5
                    }
                },
                backgroundColor: "transparent",
                markers: [{
                        latLng: [40.71, -74],
                        name: "New York"
                    },
                    {
                        latLng: [37.77, -122.41],
                        name: "San Francisco"
                    }, {
                        latLng: [-33.86, 151.2],
                        name: "Sydney"
                    }, {
                        latLng: [1.3, 103.8],
                        name: "Singapore"
                    }
                ],
                zoomOnScroll: !1
            })
        },
        e.prototype.init = function() {
            o("#dash-daterange").daterangepicker({
                    singleDatePicker: !0
                }),
                this.initCharts(), this.initMaps()
        },
        o.Dashboard = new e,
        o.Dashboard.Constructor = e
}
(window.jQuery),
function(t) {
    "use strict";
    t(document).ready(function(e) {
        t.Dashboard.init()
    })
}(window.jQuery);