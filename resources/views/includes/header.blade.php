<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="verifyownership" content="11e9bfdc9aa6f9a66cbb2a8903a32977"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link href="/css/personal.css" rel="stylesheet" type="text/css">

    <meta name="csrf-token" content="{{csrf_token()}}"/>
    <title>Cartoon</title>
    <meta name="description" content=""/>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="/">Cartoonacard</a>
    <a class="btn btn-outline-info my-2 my-sm-0" type="submit" href="/personal/logout">Logout</a>
</nav>
