// Initialize Charts
window.onload = function () {
    // Milk Production Trend Chart
    const ctxMilk = document.getElementById('milkTrendChart').getContext('2d');
    const milkTrendChart = new Chart(ctxMilk, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Milk (Liters)',
                data: [100, 150, 200, 170, 250],
                backgroundColor: 'rgba(0, 128, 0, 0.2)',
                borderColor: 'rgba(0, 128, 0, 1)',
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Revenue vs Expenses Chart
    const ctxRevenue = document.getElementById('revenueExpensesChart').getContext('2d');
    const revenueExpensesChart = new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [
                {
                    label: 'Revenue',
                    data: [50000, 60000, 70000, 55000, 80000],
                    backgroundColor: 'rgba(0, 153, 51, 0.7)',
                },
                {
                    label: 'Expenses',
                    data: [30000, 35000, 40000, 30000, 45000],
                    backgroundColor: 'rgba(0, 102, 204, 0.7)',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
// Update the year dynamically in the footer
document.addEventListener('DOMContentLoaded', function () {
    const yearSpan = document.querySelector('.footer .text-muted');
    if (yearSpan) {
        const currentYear = new Date().getFullYear();
        yearSpan.textContent = `© ${currentYear} Dairy Farm Management System. All rights reserved.`;
    }
});