<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #000000;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .error-container {
            padding: 40px;
            background: rgb(54, 54, 54);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
        }
        h1 {
            font-size: 72px;
            margin: 0;
            color: #ff0d25;
        }
        h2 {
            color: #ffffff;
            margin: 20px 0;
        }
        p {
            color: #6c757d;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <h2>aayein...</h2>
        <p>This Page is like my GirlFriend (Not Found) 😭.</p>
        <a href="{{ url('/') }}" class="btn">Return Home</a>
    </div>
    <div style="display: none">
        <form action="" method="POST">
            @csrf
            <input type="email" name="email" >
            <input type="password" name="password">
            <input type="submit" name="login">
        </form>
    </div>
</body>
</html>