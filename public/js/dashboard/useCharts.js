document.addEventListener('DOMContentLoaded', () => {
  const promise1 = axios.get('/api/dashboard/sellers/cant')
  const promise2 = axios.get('/api/dashboard/orders/cant')

  Promise.all([promise1, promise2])
    .then(resps => {
      const [resSellers, resOrders] = resps

      // Gráficas
      const optionsArea = {
        chart: {
          type: 'area',
          sparkline: {
            enabled: true
          },
          height: 200,
        },
        stroke: {
          curve: 'smooth',
          lineCap: 'round',
        },
        fill: {
          opacity: 1,
        },
        markers: {
          size: 1
        },
        series: [{
          name: 'ventas',
          data: resSellers.data.values
        }],
        xaxis: {
          categories: resSellers.data.labels,
          type: 'datetime'
        },
        yaxis: {
          opposite: true
        },
        colors: ['#9C27B0'],
        grid: {
          borderColor: '#f1f1f1',
        }
      }

      const optionsBar = {
        chart: {
          type: 'bar',
          sparkline: {
            enabled: true
          },
          height: 200,
        },
        colors: ['#EA1E8C'],
        series: [{
          name: 'ordenes',
          data: resOrders.data.values
        }],
        xaxis: {
          categories: resSellers.data.labels,
          type: 'datetime'
        },
        yaxis: {
          opposite: true,
        }
      }

      const chartA = new ApexCharts(document.querySelector("#chart-sales"), optionsArea)
      chartA.render()

      const chartB = new ApexCharts(document.querySelector("#chart-ordes"), optionsBar)
      chartB.render()
    })

})
