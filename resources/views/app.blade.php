<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> QUOTE trainer </title>

    <!-- Styles -->
    <link href="{{ mix('/css/all.css') }}" rel="stylesheet">
    <!-- <link href="/css/custom.css" rel="stylesheet">-->
</head>
<body class="bg-white-c">
<div id="app" style="height: 100%;">
    <header-component></header-component>
    <create-modal-component></create-modal-component>

    <router-view></router-view>
</div>
<!-- Scripts -->
<!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js" integrity="sha384-Qg00WFl9r0Xr6rUqNLv1ffTSSKEFFCDCKVyHZ+sVt8KuvG99nWw5RNvbhuKgif9z" crossorigin="anonymous"></script> -->
<script src="{{ mix('/js/app.js') }}" defer></script>
<script>
    let targetDate = {
        dateObject: new Date(),
        year: function(){
            return this.dateObject.getFullYear();
        },
        month: function(){
            return this.dateObject.getMonth();
        },
        date: function(){
            return this.dateObject.getDate();
        },
        day: function(){
            return this.dateObject.getDay();
        }
    }

</script>
</body>
</html>