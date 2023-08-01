<!DOCTYPE html>
<html>
<head>
  <title>Image Upload to Firebase Storage</title>
</head>
<body>
  <!-- Your HTML content goes here -->
  <input type="file" id="imageInput">
<button onclick="uploadImage()">Upload Image</button>
  <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-storage.js"></script>

  <script>
    // Your Firebase configuration settings
    var firebaseConfig = {
      apiKey: "YOUR_API_KEY",
      authDomain: "YOUR_AUTH_DOMAIN",
      projectId: "YOUR_PROJECT_ID",
      storageBucket: "YOUR_STORAGE_BUCKET",
      messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
      appId: "YOUR_APP_ID"
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);

    <script>
  function uploadImage() {
    // Get the selected image file
    var file = document.getElementById("imageInput").files[0];

    if (!file) {
      alert("Please select an image.");
      return;
    }

    // Create a Firebase Storage reference
    var storageRef = firebase.storage().ref();

    // Generate a unique name for the image file
    var imageName = "image_" + Date.now() + "_" + file.name;

    // Upload the file to Firebase Storage
    var uploadTask = storageRef.child(imageName).put(file);

    // Monitor the upload progress
    uploadTask.on(
      "state_changed",
      function (snapshot) {
        // You can handle the progress here if needed
        // For example, you can display a progress bar
      },
      function (error) {
        console.error("Error uploading image:", error);
        alert("Error uploading image. Please try again.");
      },
      function () {
        // Upload completed successfully
        // Get the download URL of the uploaded image
        uploadTask.snapshot.ref.getDownloadURL().then(function (downloadURL) {
          // Use the downloadURL here, for example:
          console.log("Image URL:", downloadURL);
          alert("Image uploaded successfully!\nImage URL: " + downloadURL);
        }).catch(function (error) {
          console.error("Error getting download URL:", error);
          alert("Error getting image URL. Please try again.");
        });
      }
    );
  }

  </script>
</body>
</html>