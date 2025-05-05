// Search Functionality
document.getElementById('searchMilkSales').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#milkSalesTable tr');

    rows.forEach(row => {
        const customerName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const batchNumber = row.querySelector('td:nth-child(9)').textContent.toLowerCase();
        if (customerName.includes(searchValue) || batchNumber.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});