<?php
?>
<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Firebase Cloud Messaging</title>
    <link rel="shortcut icon" href="favicon.png">

    <!-- Import Google Icon Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
    <style type="text/css">
        #alert,
        #info,
        #delete,
        #notification,
        #massage_row {
            display: none;
        }
    </style>
</head>
<body>
<nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container">
        <h3 class="brand-logo">Firebase Cloud Messaging</h3>
    </div>
</nav>


<main class="section no-pad-bot" id="index-banner">
    <div class="container">
        <br>
        <br>
        <div class="card-panel deep-orange darken-1 white-text z-depth-2" id="alert">
            <i class="material-icons left deep-orange-text text-darken-4">warning</i>
            <strong>Error</strong>
            <em id="alert-message"></em>
        </div>
        <div class="card-panel green darken-1 white-text z-depth-2" id="info">
            <i class="material-icons left green-text text-darken-4">info</i>
            <span id="info-message"></span>
        </div>
        <div class="row center">
            <h4 class="header col s12 light">Instance ID Token</h4>
            <p id="token" style="word-break: break-all;"></p>
        </div>
        <div class="row center">
            <button type="button" class="btn-large waves-effect waves-light orange" id="register"><i class="material-icons left">vpn_key</i> Register</button>
            <button type="button" class="btn-large waves-effect waves-light orange" id="delete"><i class="material-icons left">delete</i> Delete Token</button>
        </div>
        <div class="row">
            <form action="" method="post" class="col s12" id="notification">
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" name="title" id="title" value="Bubble Nebula" class="validate">
                        <label for="title">Notification title</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" name="body" id="body" value="It's found today at 21:00" class="validate">
                        <label for="body">Notification message</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" name="icon" id="icon" value="https://peter-gribanov.github.io/serviceworker/Bubble-Nebula.jpg" class="validate">
                        <label for="icon">URL to notification icon</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" name="click_action" id="click_action" value="https://www.nasa.gov/feature/goddard/2016/hubble-sees-a-star-inflating-a-giant-bubble" class="validate">
                        <label for="click_action">Target URL for click action</label>
                    </div>
                    <div class="input-field col s12" id="massage_row">
                        <p><strong>Massage id:</strong> <span id="massage_id" style="word-break: break-all;"></span></p>
                    </div>
                </div>
                <button type="submit" class="btn-large waves-effect waves-light orange" id="send"><i class="material-icons left">email</i> Send</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>
<script src="firebase.js"></script>
<script src="app.js"></script>