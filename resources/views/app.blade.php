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
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="/images/apple-touch-icon-180×180.png" sizes="180×180">
    <link rel="icon" type="image/png" href="/images/android-chrome-192×192.png" sizes="192×192">
    <!-- <link href="/css/custom.css" rel="stylesheet">-->
</head>
<body class="bg-white-c">
<div id="app" style="height: 100%;">
    <header-component></header-component>
    <sche-modal-component></sche-modal-component>
    <exp-modal-component></exp-modal-component>

    <router-view></router-view>
</div>
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
    let schedules = [];
    let experiences = [];
    let tags = [];
    let keyAcceptance = true;


    function isInSameWeek(dateObj1, dateObj2){
        // if(Math.ceil((dateObj1-dateObj2)/(60*60*24*1000))**2 >== 8**2 ) return false;  // 二つの時間差が8日以上であれば終了

        const firstDate1 = new Date(dateObj1.getFullYear(), dateObj1.getMonth(), dateObj1.getDate()-dateObj1.getDay());
        const firstDate2 = new Date(dateObj2.getFullYear(), dateObj2.getMonth(), dateObj2.getDate()-dateObj2.getDay());

        return firstDate1.getFullYear() === firstDate2.getFullYear() && firstDate1.getMonth() === firstDate2.getMonth() && firstDate1.getDate() === firstDate2.getDate();
    }

    function isInMonthPage(dateObj, month){
        if(dateObj.getMonth() === month){
            return true;
        }
        // 週初めor最後が指定月にかかっていればtrue
        const firstMonth = new Date(dateObj.getFullYear(), dateObj.getMonth(), dateObj.getDate()-dateObj.getDay()).getMonth();
        const lastMonth = new Date(dateObj.getFullYear(), dateObj.getMonth(), dateObj.getDate()-dateObj.getDay()+6).getMonth();

        return firstMonth === month || lastMonth === month;
    }

    function isSameDate(dateObj1, dateObj2){
        return dateObj1.getFullYear() === dateObj2.getFullYear() && dateObj1.getMonth() === dateObj2.getMonth() && dateObj1.getDate() === dateObj2.getDate();
    }

</script>
</body>
</html>