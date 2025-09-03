importScripts(
    "https://www.gstatic.com/firebasejs/11.6.0/firebase-app-compat.js"
);
importScripts(
    "https://www.gstatic.com/firebasejs/11.6.0/firebase-messaging-compat.js"
);

const firebaseConfig = {
    apiKey: "AIzaSyAxX4jUdibL9n8rJqEeEnxqPKu3S1uAQ9k",
    authDomain: "brata-cerdas-1.firebaseapp.com",
    projectId: "brata-cerdas-1",
    storageBucket: "brata-cerdas-1.firebasestorage.app",
    messagingSenderId: "319745808387",
    appId: "1:319745808387:web:de0c26d961571816af1636",
    measurementId: "G-XE79X5SH5R",
};

firebase.initializeApp(firebaseConfig);

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.onMessage((payload) => {
    console.log("Message received. ", payload);
    // ...
});
messaging.onBackgroundMessage((payload) => {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload
    );
    // Customize notification here
    const notificationTitle = payload.data?.title || "Ada Order Baru!";
    const notificationOptions = {
        body: payload.data?.body || "Cek sistem untuk melihatnya.",
        icon: "/firebase-logo.png",
        data: {
            url: "https://bratacerdas.com",
        },
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});

self.addEventListener("notificationclick", function (event) {
    event.notification.close();

    const targetUrl = event.notification.data?.url || "https://bratacerdas.com";

    event.waitUntil(
        clients
            .matchAll({ type: "window", includeUncontrolled: true })
            .then((clientList) => {
                for (const client of clientList) {
                    if (client.url === targetUrl && "focus" in client) {
                        return client.focus();
                    }
                }
                if (clients.openWindow) {
                    return clients.openWindow(targetUrl);
                }
            })
    );
});
