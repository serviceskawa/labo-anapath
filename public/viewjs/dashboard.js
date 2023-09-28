(function ($) {
    "use strict";

    function Dashboard() {
        this.$body = $("body");
        this.charts = [];
    }

    Dashboard.prototype.initCharts = function () {
        // Configuration globale des graphiques ApexCharts
        window.Apex = {
            chart: {
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                }
            },
            grid: {
                padding: {
                    left: 0,
                    right: 0
                }
            },
            colors: ["#727cf5", "#0acf97", "#fa5c7c", "#ffbc00"]
        };

        // Configuration du graphique de revenus
        var revenueChartOptions = {
            chart: {
                height: 364,
                type: "line",
                dropShadow: {
                    enabled: true,
                    opacity: 0.2,
                    blur: 7,
                    left: -7,
                    top: 7
                }
            },
            // ... Autres options spécifiques au graphique de revenus ...
        };

        // Création du graphique de revenus
        new ApexCharts(document.querySelector("#revenue-chart"), revenueChartOptions).render();

        // Configuration du graphique du produit performant
        var highPerformingProductOptions = {
            chart: {
                height: 257,
                type: "bar",
                stacked: true
            },
            // ... Autres options spécifiques au graphique du produit performant ...
        };

        // Création du graphique du produit performant
        new ApexCharts(document.querySelector("#high-performing-product"), highPerformingProductOptions).render();

        // Configuration du graphique des ventes moyennes
        var averageSalesOptions = {
            chart: {
                height: 208,
                type: "donut"
            },
            // ... Autres options spécifiques au graphique des ventes moyennes ...
        };

        // Création du graphique des ventes moyennes
        new ApexCharts(document.querySelector("#average-sales"), averageSalesOptions).render();
    };

    Dashboard.prototype.initMaps = function () {
        if ($("#world-map-markers").length > 0) {
            $("#world-map-markers").vectorMap({
                map: "world_mill_en",
                normalizeFunction: "polynomial",
                hoverOpacity: 0.7,
                hoverColor: false,
                regionStyle: {
                    initial: {
                        fill: "#e3eaef"
                    }
                },
                markerStyle: {
                    initial: {
                        r: 9,
                        fill: "#727cf5",
                        "fill-opacity": 0.9,
                        stroke: "#fff",
                        "stroke-width": 7,
                        "stroke-opacity": 0.4
                    },
                    hover: {
                        stroke: "#fff",
                        "fill-opacity": 1,
                        "stroke-width": 1.5
                    }
                },
                // ... Autres options de la carte ...
            });
        }
    };

    Dashboard.prototype.init = function () {
        $("#dash-daterange").daterangepicker({
            singleDatePicker: true
        });

        this.initCharts();
        this.initMaps();
    };

    $.Dashboard = new Dashboard();
    $.Dashboard.Constructor = Dashboard;

})(window.jQuery);

(function ($) {
    "use strict";

    $(document).ready(function () {
        $.Dashboard.init();
    });

})(window.jQuery);
