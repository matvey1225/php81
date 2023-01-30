<!doctype html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="templates/icon.jpg" >
    <title>Registration</title>
</head>
<body>
<h1>Registration</h1>

<form action="/test/index.php?ctrl=Registration" method="post">
    <input name="name" type="text" placeholder="name">
    <input name="login" type="text" placeholder="login" required>
    <input name="password" type="text" placeholder="password" required>
    <button type="submit">bur</button>
</form>
<form action="/test/index.php?ctrl=Login" method="post">
    <button type="submit">Login</button>
</form>
</body>
</html>