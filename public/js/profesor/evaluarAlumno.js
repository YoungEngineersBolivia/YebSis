// Manejo de botones de opciones
document.querySelectorAll('.option-btn').forEach(button => {
    button.addEventListener('click', function() {
        const question = this.dataset.question;
        const value = this.dataset.value;
        
        // Remover selección de otros botones de la misma pregunta
        document.querySelectorAll(`[data-question="${question}"]`).forEach(btn => {
            btn.classList.remove('selected-si', 'selected-no', 'selected-proceso');
        });
        
        // Marcar este botón como seleccionado según su valor
        if (value === 'si') {
            this.classList.add('selected-si');
        } else if (value === 'no') {
            this.classList.add('selected-no');
        } else if (value === 'en_proceso') {
            this.classList.add('selected-proceso');
        }
        
        // Actualizar el campo oculto
        document.getElementById(`${question}_value`).value = value;
    });
});

// Validación del formulario
document.getElementById('evaluationForm').addEventListener('submit', function(e) {
    const participa = document.getElementById('participa_value').value;
    const secuenciada = document.getElementById('secuenciada_value').value;
    const paciente = document.getElementById('paciente_value').value;
    
    if (!participa || !secuenciada || !paciente) {
        e.preventDefault();
        alert('Por favor, responde todas las preguntas antes de guardar la evaluación.');
    }
});