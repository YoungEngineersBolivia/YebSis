document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const studentItems = document.querySelectorAll('.student-item');
    
    studentItems.forEach(item => {
        const name = item.querySelector('.student-name').textContent.toLowerCase();
        const program = item.querySelector('.student-program').textContent.toLowerCase();
        
        item.style.display = (name.includes(searchTerm) || program.includes(searchTerm)) ? 'flex' : 'none';
    });
});