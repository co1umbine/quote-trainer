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

    function isInSameWeek(dateObj1, dateObj2){
        // if(Math.ceil((dateObj1-dateObj2)/(60*60*24*1000))**2 >== 8**2 ) return false;  // 二つの時間差が8日以上であれば終了

        const firstDate1 = new Date(dateObj1.getFullYear(), dateObj1.getMonth(), dateObj1.getDate()-dateObj1.getDay()).getTime();
        const firstDate2 = new Date(dateObj2.getFullYear(), dateObj2.getMonth(), dateObj2.getDate()-dateObj2.getDay()).getTime();

        return firstDate1 === firstDate2;
    }

    function isInSameMonthPage(dateObj1, dateObj2){
        if((Math.ceil((dateObj1-dateObj2)/(60*60*24*1000))**2) >= 42**2 ) return false;  // 二つの時間差が42日以上であれば終了
        if(dateObj1.getMonth() === dateObj2.getMonth()){
            return true;
        }
        const firstDate1 = new Date(dateObj1.getFullYear(), dateObj1.getMonth(), dateObj1.getDate()-dateObj1.getDay()).getMonth();
        const lastDate1 = new Date(dateObj1.getFullYear(), dateObj1.getMonth(), dateObj1.getDate()-dateObj1.getDay()+6).getMonth();

        const firstDate2 = new Date(dateObj2.getFullYear(), dateObj2.getMonth(), dateObj2.getDate()-dateObj2.getDay()).getMonth();
        const lastDate2 = new Date(dateObj2.getFullYear(), dateObj2.getMonth(), dateObj2.getDate()-dateObj2.getDay()+6).getMonth();

        return firstDate1 === firstDate2 || firstDate1 === lastDate2 || lastDate1 === firstDate1 || lastDate1 === lastDate2;
    }

    function isSameDate(dateObj1, dateObj2){
        return dateObj1.getFullYear() === dateObj2.getFullYear() && dateObj1.getMonth() === dateObj2.getMonth() && dateObj1.getDate() === dateObj2.getDate();
    }

</script>
</body>
</html>