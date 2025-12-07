import Chart from 'chart.js/auto';

window.drawChart = (canvasId, chartData) => {
    const canvas = document.getElementById(canvasId);
    if (!canvas) {
        console.warn("Canvas not found:", canvasId);
        return;
    }

    const ctx = canvas.getContext('2d');

    // Destroy previous instance if it exists
    if (window[canvasId] instanceof Chart) {
        window[canvasId].destroy();
    }

    // Start from any options passed from PHP
    const baseOptions = chartData.options || {};

    // Always make charts responsive
    const mergedOptions = {
        responsive: true,
        maintainAspectRatio: false,
        ...baseOptions,
    };

    // ---- FORCE INTEGER TICKS (no .5) ----
    mergedOptions.scales = mergedOptions.scales || {};

    // If chart is horizontal (indexAxis: 'y'), numeric axis is X; otherwise Y
    const numericAxisKey =
        mergedOptions.indexAxis === 'y' ? 'x' : 'y';

    mergedOptions.scales[numericAxisKey] =
        mergedOptions.scales[numericAxisKey] || {};

    mergedOptions.scales[numericAxisKey].ticks = {
        ...(mergedOptions.scales[numericAxisKey].ticks || {}),
        precision: 0,   // no decimal places
        stepSize: 1,    // 0,1,2,3,... (no .5)
    };
    // -------------------------------------

    window[canvasId] = new Chart(ctx, {
        type: chartData.type ?? 'bar',
        data: chartData.data,
        options: mergedOptions,
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

    bindChart('render-age-chart', 'ageChart');
    bindChart('render-device-chart', 'deviceChart');
    bindChart('render-cause-chart', 'causeChart');
    bindChart('render-location-chart', 'locationChart');
    bindChart('render-trends-chart', 'trendsChart');
});
