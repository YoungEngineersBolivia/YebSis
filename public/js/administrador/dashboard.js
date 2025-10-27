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
                    callback: function(value) {
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

// Gráfico de ingresos mensuales
const ctxMensuales = document.getElementById('ingresosMensuales').getContext('2d');
new Chart(ctxMensuales, {
    type: 'bar',
    data: {
        labels: window.mesesPorMes,
        datasets: [{
            label: 'Ingresos Mensuales',
            data: window.ingresosPorMes,
            backgroundColor: 'rgba(40, 167, 69, 0.8)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 1
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
                    callback: function(value) {
                        return 'Bs ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
