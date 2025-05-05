// Search Functionality
document.getElementById('searchAnimal').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#animalTable tr');

    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const breed = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        if (name.includes(searchValue) || breed.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Edit Button Functionality
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function () {
        const animalId = this.getAttribute('data-id');
        alert(`Edit animal with ID: ${animalId}`);
        // Add logic to fetch and populate edit modal
    });
});

// Delete Button Functionality
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function () {
        const animalId = this.getAttribute('data-id');
        if (confirm('Are you sure you want to delete this animal?')) {
            fetch(`delete_animal.php?id=${animalId}`, { method: 'DELETE' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Animal deleted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error deleting animal.');
                    }
                });
        }
    });
});