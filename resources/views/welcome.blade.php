<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wellcome</title>
</head>
<body>
<center>
    <h1>Welcome to my website</h1>
    <a href="{{ url('/index') }}">Go to index</a>
    <a href="{{ url('/mypage') }}">Go to mypage</a>
</n>
</n>
</n>
</n>
<h3>Fill this form please</h3>
<form>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="note">note:</label><br>
    <textarea id="note" name="note" rows="4" cols="50"></textarea><br><br>
    <input type="submit" value="Submit">
</form>

</center>
</body>
</html>
