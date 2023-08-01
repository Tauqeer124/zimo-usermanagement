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
      
    apiKey: "AIzaSyBF-MRCHGtX3oHMWR6MIcDR7pJxQTypk1g",
  authDomain: "imageupload-11920.firebaseapp.com",
  projectId: "imageupload-11920",
  storageBucket: "imageupload-11920.appspot.com",
  messagingSenderId: "257506850858",
  appId: "1:257506850858:web:a75f5f1bc705983db02b53",
}

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);

    
  function uploadImage() {
    // Get the selected image file
    var file = document.getElementById("imageInput").files[0];

    if (!file) {
      alert("Please select an image.");
      return;
    }

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

      // Send the image URL to the Laravel API endpoint
      fetch('/uploadimage', {
        method: 'POST',
       
        
      })
      
      .then(data => {
        console.log(data.message); // Output: "Image URL stored successfully"
      })
      .catch(error => {
        console.error('Error uploading image URL:', error);
        alert('Error uploading image URL. Please try again.');
      });

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