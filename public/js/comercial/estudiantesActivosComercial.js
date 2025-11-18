    const monthFmt = new Intl.DateTimeFormat('es-BO', { month: 'short' });
    const labels = rawSeries.map(s => monthFmt.format(new Date(s.mes_iso + 'T00:00:00')));
    const data   = rawSeries.map(s => Number(s.cantidad));

    const ctx = document.getElementById('chartActivos').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Estudiantes Activos',
                data,
                tension: 0.35,
                borderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 6,
                fill: true,
                borderColor: 'rgba(121, 80, 242, 1)',
                backgroundColor: 'rgba(121, 80, 242, 0.12)',
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(121, 80, 242, 1)'
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });