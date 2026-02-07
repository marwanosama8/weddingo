importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
   
firebase.initializeApp({
    apiKey: "AIzaSyD5RuQZWcCy-zLNeUKNwa1VFYwlODbQScA",
    projectId: "smug-533e1",
    messagingSenderId: "286636214740",
    appId: "1:286636214740:web:57ab0679f8d9750b2e2e41"
});
  

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});