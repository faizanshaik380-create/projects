document.addEventListener("DOMContentLoaded", function () {

    const chartCanvas = document.getElementById("expenseChart");

    if (!chartCanvas) {
        console.log("Canvas not found");
        return;
    }

    let labels = chartCanvas.getAttribute("data-labels");
    let values = chartCanvas.getAttribute("data-values");

    if (!labels || !values) {
        console.log("No chart data");
        return;
    }

    labels = JSON.parse(labels);
    values = JSON.parse(values);

    if (labels.length === 0) {
        console.log("Empty data");
        return;
    }

    new Chart(chartCanvas, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#ff6384',
                    '#36a2eb',
                    '#ffcd56',
                    '#4bc0c0',
                    '#9966ff',
                    '#ff9f40'
                ]
            }]
        }
    });

});