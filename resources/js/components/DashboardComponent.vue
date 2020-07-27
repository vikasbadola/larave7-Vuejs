<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <highcharts :options="chartOptions"></highcharts>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        beforeCreate() {
            axios.get("/api/dashboard")
               .then(resp => {
                let self = this;
                $.each(resp.data.minutes[0], function(key, value) {
                    self.chartOptions.xAxis.categories.push(value);
                });
                $.each(resp.data.minutes[0], function(key, value) {
                    self.chartOptions.xAxis.categories.push(value);
                });
                $.each(resp.data.pendingTasks[0], function(key, value) {
                    self.chartOptions.series[1].data.push(value);
                });
                this.chartOptions.series[0].data = resp.data.expecTasks[0];
              });
        },
        data() {
           return {
             chartOptions: {
                title: {
      text: 'Tasks Burndown Chart',
      x: -20 //center
    },
    colors: ['blue', 'red'],
    plotOptions: {
      line: {
        lineWidth: 3
      },
      tooltip: {
        hideDelay: 200
      }
    },
    credits: {
        enabled: false
    },
    xAxis: {
      categories: []
    },
    yAxis: {
      title: {
        text: 'Tasks'
      },
      plotLines: [{
        value: 0,
        width: 1
      }]
    },
    tooltip: {
      valueSuffix: ' tasks',
      crosshairs: true,
      shared: true
    },
    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'middle',
      borderWidth: 0
    },
    series: [{
      name: 'Total Tasks',
      color: 'rgba(255,0,0,0.25)',
      lineWidth: 2,
      data: []
    }, {
      name: 'Pending Tasks',
      color: 'rgba(0,120,200,0.75)',
      marker: {
        radius: 6
      },
      data: []
    }]
             }
           }
        },
    }
</script> 