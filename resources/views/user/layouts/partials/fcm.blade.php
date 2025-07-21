@push('js')
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>

    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>

    <script type="module">
        @include('firebase-config');

        firebase.initializeApp(firebaseConfig);

        const channel = new BroadcastChannel('sw-messages');
        const messaging = firebase.messaging();

        messaging.requestPermission()
            .then(() => {
                return messaging.getToken()
            })
            .then((token) => {})
            .catch((err) => {
                console.log('Permission is not granted');
            })

        messaging.onMessage((payload) => {
            $('#alert_link').attr("href", payload.data.link);
            $('#alertModal').modal('show');
        });

        channel.addEventListener('message', event => {
            $('#alert_link').attr("href", event.data.payload.data.link);
            $('#alertModal').modal('show');
        });
    </script>

@endpush
