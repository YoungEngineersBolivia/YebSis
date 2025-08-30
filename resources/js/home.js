lottie.loadAnimation({
        container: document.getElementById('whatsapp-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: "{{ asset('animaciones/whatsapp.json') }}" // tu JSON
    });