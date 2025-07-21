<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>

<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>

<script type="module">

    @include('firebase-config');

    firebase.initializeApp(firebaseConfig);

    const messaging = firebase.messaging();

    messaging.getToken().then((currentToken) => {
        if (currentToken) {
            let fcm_token = $("#fcm_token").val(currentToken);
        } else {
            console.log('No registration token available. Request permission to generate one.');
        }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);
        });

</script>
