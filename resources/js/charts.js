import Chart from 'chart.js/auto';

window.drawChart = (canvasId, chartData) => {
    const canvas = document.getElementById(canvasId);
    if (!canvas) {
        console.warn("Canvas not found:", canvasId);
        return;
    }

    const ctx = canvas.getContext('2d');

    // Only destroy if it’s an actual Chart instance
    if (window[canvasId] instanceof Chart) {
        window[canvasId].destroy();
    }

    window[canvasId] = new Chart(ctx, {
        type: chartData.type ?? 'bar',
        data: chartData.data,
        options: chartData.options ?? {
            responsive: true,
            maintainAspectRatio: false
        }
    });
};

// Livewire v3: use 'livewire:navigated'
console.log("TEST: charts.js loaded");

document.addEventListener('livewire:navigated', () => {
    console.log("Livewire v3 navigated fired – charts.js is now listening");

    const bindChart = (eventName, canvasId) => {
        Livewire.on(eventName, (payload) => {
            let chartData = Array.isArray(payload) ? payload[0] : payload;
            console.log(`Received data for ${eventName}`, chartData);
            drawChart(canvasId, chartData);
        });
    };

    bindChart('render-applications-chart', 'applicationsChart');
    bindChart('render-status-chart', 'statusChart');
    bindChart('render-gender-chart', 'genderChart');
    bindChart('render-disability-chart', 'disabilityChart');
    bindChart('render-location-chart', 'locationChart');
    bindChart('render-trends-chart', 'trendsChart');
});
