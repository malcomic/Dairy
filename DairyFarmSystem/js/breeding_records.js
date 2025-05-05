// Search Functionality
document.getElementById('searchBreeding').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#breedingTable tr');

    rows.forEach(row => {
        const animalName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const breedingMethod = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
        if (animalName.includes(searchValue) || breedingMethod.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});