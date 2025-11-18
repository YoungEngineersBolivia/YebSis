Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
Chart.defaults.color = '#666';

// Gráfico de ingresos diarios
const ctxDiarios = document.getElementById('ingresosDiarios').getContext('2d');
new Chart(ctxDiarios, {
    type: 'line',
    data: {
        labels: window.fechasPorDia,
        datasets: [{
            label: 'Ingresos Diarios',
            data: window.ingresosPorDia,
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function (value) {
                        return 'Bs ' + value.toLocaleString();
                    }
                }
            }
        },
        elements: {
            point: { radius: 4, hoverRadius: 8 }
        }
    }
});

// Gráfico de comparativa anual (Ingresos vs Egresos)
const ctxMensuales = document.getElementById('comparativaAnual').getContext('2d');
new Chart(ctxMensuales, {
    type: 'bar',
    data: {
        labels: window.graficoAnual.labels,
        datasets: [
            {
                label: 'Ingresos',
                data: window.graficoAnual.ingresos,
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            },
            {
                label: 'Egresos',
                data: window.graficoAnual.egresos,
                backgroundColor: 'rgba(220, 53, 69, 0.8)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function (value) {
                        return 'Bs ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
