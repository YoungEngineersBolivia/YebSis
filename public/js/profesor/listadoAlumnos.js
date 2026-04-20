document.getElementById('searchInput').addEventListener('input', function (e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const studentItems = document.querySelectorAll('.student-item');

    studentItems.forEach(item => {
        const name = item.querySelector('.student-name')?.textContent.toLowerCase() || "";
        const program = item.querySelector('.student-program')?.textContent.toLowerCase() || "";

        if (name.includes(searchTerm) || program.includes(searchTerm)) {
            item.classList.remove('d-none');
        } else {
            item.classList.add('d-none');
        }
    });
});