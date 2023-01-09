Ext.namespace('bxSender.chart');

bxSender.panel.chart = function (config) {
    config = config || {}

    this.selectedStatus = 'all'
    this.chartPanel = null
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        hideMode: 'offsets',
        items: [{
            title: _('bxsender_queue_chart_mailing_statistics'),
            xtype: 'fieldset',
            items: [
                {
                    id: 'bxsender-chart-panel-state'
                }
            ]
        }]
    })
    bxSender.panel.chart.superclass.constructor.call(this, config)
}
Ext.extend(bxSender.panel.chart, MODx.Panel, {
    resetSelected: function () {
        var className = 'highcharts-legend-item'
        var $legend, elem = ''
        var elements = document.getElementsByClassName(className)
        for (var i = 0; i < elements.length; i++) {
            elem = elements[i]
            $legend = Ext.get(elem)
            if ($legend.dom.localName === 'div') {
                if ($legend.hasClass('chart-selected')) {
                    $legend.removeClass('chart-selected')
                }
            }
        }
        this.selectedStatus = 'all'
    },
    Chart: function (mode, v, text) {
        if (!Ext.get('bxsender-highlight-' + mode)) {
            container = Ext.get('bxsender-chart-panel-' + mode)
            Ext.DomHelper.append(container, {
                tag: 'div',
                id: 'bxsender-highlight-' + mode
            })
        }

        var id = 'bxsender-highlight-' + mode
        Highcharts.chart(id, {
            chart: {
                height: 330,
                minHeight: 330,
                minWidth: 350,
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
            },
            title: {
                text: null
            },
            tooltip: {
                pointFormat: '{point.caption}: <b>{point.percentage:.1f}%</b>',
                percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    legendType: 'point',
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true,
                    point: {
                        events: {
                            legendItemClick: function (event) {

                                var fieldStatus = Ext.getCmp('bxsender-form-queue-status')
                                fieldStatus.setValue(event.target.field)
                                var formQueue = Ext.getCmp('bxsender-form-queue')

                                // Сброс классов
                                var chart = Ext.getCmp('bxsender-panel-chart')
                                chart.resetSelected()
                                chart.selectedStatus = this.field

                                // Отключаем обновление графика
                                formQueue.isDirtyChart = false
                                formQueue.fireEvent('change')

                                $legend = Ext.get(this.legendGroup.div)
                                $legend.hasClass('chart-selected') ? $legend.removeClass('chart-selected') : $legend.addClass('chart-selected')
                                return false
                            },
                        }
                    }
                }
            },
            legend: {
                enabled: true,
                useHTML: true,
                align: 'right',
                verticalAlign: 'middle',
                layout: 'vertical',
                itemMarginTop: 5,
                itemMarginBottom: 5,
                x: -20,
                labelFormatter: function () {
                    $legend = Ext.get(this.legendGroup.div)
                    this.selected_status !== true ? $legend.removeClass('chart-selected') : $legend.addClass('chart-selected')

                    var count = '';
                    if (this.field !== 'all') {
                        count = this.count + ' (' + Math.round(this.percentage) + '%)';
                    } else {
                        count = this.count;
                    }

                    return '<div class="bxsender-chart-element"><div class="bxsender-chart-element-caption"><span title="'+this.description+'">' + this.caption + '</span></div>  <div class="bxsender-chart-element-count">' + count + '</div></div>'
                },
                itemStyle: {
                    fontWeight: 'normal'
                }
            },
            series: [{
                name: _('bxsender_queue_chart_percent'),
                colorByPoint: true,
                data: this.getItems(mode, v)
            }],
            credits: {
                enabled: false
            }
        }, function (chart) {

            var panelChart = Ext.getCmp('bxsender-panel-chart');

            // add link all message
            chart.addSeries({data:[{
                field: 'all',
                selected_status: panelChart.selectedStatus === 'all',
                name: 'all',
                description: _('bxsender_chart_all_message'),
                caption: _('bxsender_chart_all_message'),
                color: '#FFFFFF',
                y: 0,
                count: bxSender.сhart['state'][0]['count'],
            }]});

            /*chart.renderer.button(_('bxsender_chart_all_message'),280, 20).attr({zIndex: 1}).on('click', function () {
                chart.addSeries({data:[11]});
            }).add();*/

        })
    },
    getItems: function (mode, v) {

        var selectedStatus = this.selectedStatus
        var charts = []

        var name,percent,caption,count,color = null;
        for (i = 0; i < bxSender.сhart[mode].length; i++) {
            var field = bxSender.сhart[mode][i]
            if (field) {
                
                name = field['field']
                if (name === 'all') {
                    continue
                }

                description = field['description']
                caption = field['caption']
                count = field['count']
                percent = bxSender.сhart[v][name]['percent']
                color = field['color']
                var item = {
                    field: name,
                    selected_status: selectedStatus === name,
                    name: name,
                    description: description,
                    caption: caption,
                    y: percent,
                    count: count,
                    color: color,
                    val: i + 1
                }
                charts.push(item)
            }
        }
        return charts
    }
})
Ext.reg('bxsender-panel-chart', bxSender.panel.chart)
