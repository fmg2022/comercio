document.addEventListener('DOMContentLoaded', () => {
  // Gráficas
  const optionsArea = {
    chart: {
      type: 'area',
      sparkline: {
        enabled: true
      },
    },
    stroke: {
      curve: 'smooth'
    },
    fill: {
      opacity: 1,
    },
    series: [{
      name: 'sales',
      data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
    }],
    colors: ['#9C27B0'],
  }

  const optionsBar = {
    chart: {
      type: 'bar',
      sparkline: {
        enabled: true
      },
    },
    colors: ['#EA1E8C'],
    series: [{
      name: 'orders',
      data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
    }],
    yaxis: {
      opposite: true,
    }
  }

  const chartA = new ApexCharts(document.querySelector("#chart-sales"), optionsArea)
  const chartB = new ApexCharts(document.querySelector("#chart-ordes"), optionsBar)

  chartA.render()
  chartB.render()
})