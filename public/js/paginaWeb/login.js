 function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.password-toggle');
            if (!passwordInput) return;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = '👁‍🗨';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = '👁';
            }
        }

        // Animación de los engranajes
        document.addEventListener('DOMContentLoaded', function() {
            const gears = document.querySelectorAll('.gear');
            gears.forEach((gear, index) => {
                gear.style.animation = `rotate ${2 + index}s linear infinite`;
            });
        });

        // CSS Animation para los engranajes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes rotate {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);