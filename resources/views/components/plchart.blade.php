<div class="d-flex scheme">
    <div id="chartdiv" class="mt-20"></div>
    @if(isset($current_bullet))
        <?php
        ?>
        <div class="d-flex flex-column info">
            <div><b>{{ $date_period }}</b> is Here</div>
            <?php if ($color == 'green') : ?>
            <div class="d-flex align-items-center mark">
                <i class="dot green"></i>
                <div>
                    <b>Green:</b>
                    <span>profitable</span>
                </div>
            </div>
            <?php else: ?>
            <div class="d-flex align-items-center mark">
                <i class="dot red"></i>
                <div>
                    <b>Red:</b>
                    <span>unprofitable</span>
                </div>
            </div>
            <?php endif; ?>
            <div class="chart-info d-flex flex-column">
                <span><b>MCaPoS:</b>  {{ number_format($current_bullet[0]['x'], 2, '.', ',') }}</span>
                <span><b>Revenue:</b> ${{ number_format($current_bullet[0]['y'], 2, '.', ',') }}</span>
                <i class="icon-info">
                    <img src="{{ asset('frontend/images/dist/icons/i.svg') }}" alt="">
                </i>
                <span class="help">Marketing cost as percentage of sales</span>
            </div>
        </div>
    @endif
</div>
@if(isset($chart_data))
    <script>
        am5.ready(function () {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("chartdiv");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: true,
                panY: true,
                wheelY: "zoomXY",
                pinchZoomX: true,
                pinchZoomY: true
            }));

            chart.get("colors").set("step", 2);

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
            var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererX.new(root, {}),
                maxDeviation: 0.3,
            }));

            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererY.new(root, {}),
                maxDeviation: 0.3,
            }));

            var tooltip = am5.Tooltip.new(root, {
                labelText: "MCaPos: {valueY}\nRN: ${valueY}",
                getFillFromSprite: false,
                getStrokeFromSprite: false,
                autoTextColor: false,
                getLabelFillFromSprite: false,
            });

            tooltip.get('background').setAll({
                fill: am5.color('#ffffff'),
                strokeWidth: 0,
            })

            tooltip.label.setAll({
                fill: am5.color('#000000')
            });

            console.log(tooltip)

// Create series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
            var series0 = chart.series.push(am5xy.LineSeries.new(root, {
                calculateAggregates: true,
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "y",
                valueXField: "x",
                valueField: "value",
                tooltip,
            }));

            // Create series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
            var series = chart.series.push(am5xy.LineSeries.new(root, {
                name: "Series 1", xAxis, yAxis,
                valueYField: "y",
                valueXField: "x",
                tooltip: am5.Tooltip.new(root, {
                    labelText: "{valueY}"
                })
            }));
            series.strokes.template.setAll({
                strokeWidth: 2,
                strokeDasharray: [3, 3]
            });

            series.data.setAll(<?= $chart_data ?>);

            var circleTemplate = am5.Template.new({});

            series0.bullets.push(function () {
                var graphics = am5.Circle.new(root, {
                    fill: am5.color('{{ $hex }}'),
                    tooltip: tooltip
                }, circleTemplate);
                return am5.Bullet.new(root, {
                    sprite: graphics,
                    tooltip: tooltip,
                    radius: 1
                });
            });

// Add heat rule
// https://www.amcharts.com/docs/v5/concepts/settings/heat-rules/
            series0.set("heatRules", [{
                target: circleTemplate,
                min: 3,
                max: 35,
                dataField: "value",
                key: "radius"
            }]);

// Add bullet
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/#Bullets
            var starTemplate = am5.Template.new({});

            series0.strokes.template.set("strokeOpacity", 0);
            series0.set('tooltip', tooltip);

// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
            chart.set("cursor", am5xy.XYCursor.new(root, {
                xAxis,
                yAxis,
                snapToSeries: [series0, series]
            }));
            <?php if (isset($current_bullet)) : ?>
            series0.data.setAll(<?= json_encode($current_bullet) ?>);
            series0.appear(1000);
            <?php endif; ?>

            // Make stuff animate on load
            // https://www.amcharts.com/docs/v5/concepts/animations/
            series.appear(1000);
            chart.appear(1000, 100);

        }); // end am5.ready()
    </script>
@endif
