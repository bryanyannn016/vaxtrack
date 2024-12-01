<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Apply Inter font globally and reset margin/padding */
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        /* Navbar styling */
        .navbar {
            background-color: #2974E5;
            padding: 15px;
            color: white;
            display: flex;
            justify-content: left;
            align-items: center;
            height: 80px;
            width: 100%;
        }

        .navbar img {
            width: 110px;
           
            height:auto;
        }

        .navbar h1 {
            font-size: 20px;
            margin: 0;
        }

        /* Container for form and image */
        .content-container {
            display: flex;
            margin-top: 40px;
            padding: 20px;
        }

        /* Styling for the image on the left */
        .content-container img {
            height: 400px;
            margin-right: 100px;
            margin-left: 250px;
        }

        /* Form container styling */
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .form-container .form-group {
            margin-bottom: 15px;
            width: 100%;
        }

        .form-container input {
            padding: 10px;
            width: 300px;
            font-size: 14px;
        }

        .form-container button {
            padding: 10px;
            background-color: #112F7B;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 120px;
            margin-right: 70px;
            margin-top: 10px;
        }

        .form-container button:hover {
            background-color: #0d1a44;
        }

        .form-container .sign-up-link {
            font-size: 14px;
        }

        .form-container .sign-up-link a {
            color: #112F7B;
            text-decoration: none;
        }

        .form-container .sign-up-link a:hover {
            text-decoration: underline;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 5px;
            width: 80%;
            max-width: 700px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 18px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .modal-body {
            padding: 10px 0;
        }

        .modal-footer {
            padding: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
        }

        .modal-footer button {
            padding: 10px;
            background-color: #112F7B;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .modal-footer button:hover {
            background-color: #0d1a44;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <img src="{{ asset('/vaxtrack-logo.png') }}" alt="VaxTrack Logo">
        <h1 style="font-size: 25px;">VaxTrack</h1>
    </div>

    <!-- Content with image and form -->
    <div class="content-container">
        <!-- Image on the left -->
        <img src="{{ asset('/login-display.png') }}" alt="Login Display Image">

        <!-- Login Form on the right -->
        <div class="form-container">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Displaying error message if any -->
                @if ($errors->any())
                    <div class="form-group" style="color: red;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group" style="margin-left:200px;">
                    <button type="submit" style="margin-top:10px;">Login</button>
                </div>
            </form>

            <!-- Sign Up Link -->
            <div class="sign-up-link" style="margin-left:50px;">
                <p>Don't have an account? <a href="{{ route('register') }}">Sign up!</a></p>
            </div>
        </div>
    </div>

    <!-- Modal for Privacy Notice -->
    <div id="privacyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>PRIVACY NOTICE</h2>
                <h2>VAXTRACK</h2>
            </div>
            <div class="modal-body">
                <p>Your privacy is important to us. Please read and accept our Privacy Notice to proceed.</p>
                <label>
                    <input type="checkbox" id="privacyAgree" required>
                    I have read and agree to the Privacy Notice.
                </label>
            </div>
            <div class="modal-footer">
                <button onclick="confirmPrivacy()">Confirm</button>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal -->
    <script>
        // Function to show the modal
        function showModal() {
            document.getElementById('privacyModal').style.display = 'flex';
        }

        // Function to hide the modal
        function confirmPrivacy() {
            if (document.getElementById('privacyAgree').checked) {
                document.getElementById('privacyModal').style.display = 'none';
            } else {
                alert('You must agree to the Privacy Notice to proceed.');
            }
        }

        // Show the modal on page load
        window.onload = showModal;
    </script>

</body>
</html>
