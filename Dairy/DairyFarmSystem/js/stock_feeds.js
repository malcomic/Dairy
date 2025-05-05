// Search Functionality for Feed Inventory Table
document.getElementById('searchInventory').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#inventoryTable tr');

    rows.forEach(row => {
        const feedName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        if (feedName.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Search Functionality for Feed Purchases Table
document.getElementById('searchPurchases').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#purchasesTable tr');

    rows.forEach(row => {
        const supplier = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
        if (supplier.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Search Functionality for Feed Usage Table
document.getElementById('searchUsage').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#usageTable tr');

    rows.forEach(row => {
        const animalName = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
        if (animalName.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});