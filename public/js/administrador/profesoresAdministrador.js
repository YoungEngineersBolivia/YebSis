 (function () {
        const input = document.querySelector('input[name="search"]');
        const table = document.getElementById('teachersTable');
        if (!input || !table) return;

        input.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    })();