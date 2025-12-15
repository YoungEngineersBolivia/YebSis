// Manejo de botones de opciones (dinámico)
document.querySelectorAll('.option-btn').forEach(button => {
    button.addEventListener('click', function () {
        const question = this.dataset.question;
        const value = this.dataset.value;

        // Remover selección de otros botones de la misma pregunta
        document.querySelectorAll(`[data-question="${question}"]`).forEach(btn => {
            btn.classList.remove('selected-si', 'selected-no', 'selected-proceso');
        });

        // Marcar este botón como seleccionado según su valor (ID de respuesta)
        // 1 = Sí, 2 = No, 3 = En proceso
        if (value === '1') {
            this.classList.add('selected-si');
        } else if (value === '2') {
            this.classList.add('selected-no');
        } else if (value === '3') {
            this.classList.add('selected-proceso');
        }

        // Actualizar el campo oculto
        document.getElementById(`${question}_value`).value = value;
    });
});

// Validación del form ulario
document.getElementById('evaluationForm').addEventListener('submit', function (e) {
    // Validar que se seleccionó un modelo
    const modeloSelect = document.getElementById('modelo_select');
    if (!modeloSelect || !modeloSelect.value) {
        e.preventDefault();
        alert('Por favor, selecciona un modelo antes de guardar la evaluación.');
        return;
    }

    // Validar que todas las preguntas fueron respondidas
    const allHiddenInputs = document.querySelectorAll('input[type="hidden"][name^="respuestas"]');
    let allAnswered = true;

    allHiddenInputs.forEach(input => {
        if (!input.value || input.value === '') {
            allAnswered = false;
        }
    });

    if (!allAnswered) {
        e.preventDefault();
        alert('Por favor, responde todas las preguntas antes de guardar la evaluación.');
        return;
    }
});