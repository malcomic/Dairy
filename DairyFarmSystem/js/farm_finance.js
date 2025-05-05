// Search Functionality for Income Table
document.getElementById('searchIncome').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#incomeTable tr');

    rows.forEach(row => {
        const source = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        if (source.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Search Functionality for Expense Table
document.getElementById('searchExpense').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#expenseTable tr');

    rows.forEach(row => {
        const category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        if (category.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Search Functionality for Budget Table
document.getElementById('searchBudget').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#budgetTable tr');

    rows.forEach(row => {
        const budgetName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        if (budgetName.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Search Functionality for Loan Table
document.getElementById('searchLoan').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#loanTable tr');

    rows.forEach(row => {
        const lender = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        if (lender.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});