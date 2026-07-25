import { Chart, LineController, LineElement, PointElement, LinearScale, CategoryScale, Filler, Tooltip } from 'chart.js';

Chart.register(LineController, LineElement, PointElement, LinearScale, CategoryScale, Filler, Tooltip);

function renderLineChart(canvas, color, { integerTicks = false } = {}) {
    const labels = JSON.parse(canvas.dataset.labels || '[]');
    const values = JSON.parse(canvas.dataset.values || '[]');
    const maxValue = values.length ? Math.max(...values) : 0;

    new Chart(canvas, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data: values,
                borderColor: color,
                backgroundColor: color + '1a',
                fill: true,
                tension: 0.3,
                pointRadius: 0,
                borderWidth: 2,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                x: { ticks: { maxTicksLimit: 8 } },
                y: {
                    beginAtZero: true,
                    // Flat/near-zero series would otherwise let Chart.js pick an
                    // arbitrary axis max, making the line look artificially tall.
                    suggestedMax: maxValue === 0 ? 1 : undefined,
                    ticks: integerTicks ? { precision: 0 } : undefined,
                },
            },
        },
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const invoicesChart = document.getElementById('invoicesChart');
    if (invoicesChart) {
        renderLineChart(invoicesChart, '#4f46e5', { integerTicks: true });
    }

    const paymentsChart = document.getElementById('paymentsChart');
    if (paymentsChart) {
        renderLineChart(paymentsChart, '#16a34a');
    }
});
