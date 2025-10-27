document.addEventListener('DOMContentLoaded', function() {
    // Datos desde PHP
    
    
    // Preparar datos para Chart.js
    const labels = datosGrafica.meses.map(m => m.nombre);
    const programas = datosGrafica.programas;
    
    // Colores para las barras
    const coloresActual = [
        '#6f42c1', '#7c4dff', '#9c27b0', '#e91e63', '#f44336',
        '#ff9800', '#4caf50', '#00bcd4', '#2196f3', '#3f51b5'
    ];
    
    const coloresAnterior = coloresActual.map(color => color + '80'); // Más transparente
    
    const datasets = [];
    
    // Dataset para cada programa (año actual)
    programas.forEach((programa, index) => {
        const dataActual = labels.map(mes => datosGrafica.datos[mes].actual[programa] || 0);
        const dataAnterior = labels.map(mes => datosGrafica.datos[mes].anterior[programa] || 0);
        
        // Datos año actual
        datasets.push({
            label: programa + ' (Actual)',
            data: dataActual,
            backgroundColor: coloresActual[index % coloresActual.length],
            borderColor: coloresActual[index % coloresActual.length],
            borderWidth: 1,
            barThickness: 20
        });
        
        // Datos año anterior
        datasets.push({
            label: programa + ' (Anterior)',
            data: dataAnterior,
            backgroundColor: coloresAnterior[index % coloresAnterior.length],
            borderColor: coloresActual[index % coloresActual.length],
            borderWidth: 1,
            barThickness: 20
        });
    });

    // Configuración del gráfico
    const ctx = document.getElementById('talleresChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: false
                },
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' estudiantes';
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    grid: {
                        display: false
                    }
                },
                y: {
                    display: true,
                    beginAtZero: true,
                    grid: {
                        color: '#f8f9fa'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + 'k';
                        }
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
});