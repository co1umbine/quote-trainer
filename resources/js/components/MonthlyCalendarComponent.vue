<template>
    <div class="container-xxl" style="" id="bodyBase">
        <table width="100%" rurles="all" class="table mx-lg-0">
            <tr class="base-c small">
                <th class="day-of-week high-c">SUN</th>
                <th class="day-of-week">MON</th>
                <th class="day-of-week">TUE</th>
                <th class="day-of-week">WED</th>
                <th class="day-of-week">THU</th>
                <th class="day-of-week">FRI</th>
                <th class="day-of-week">SAT</th>
            </tr>
            <tr class="cal-row-week" id="w1"> 
            </tr>
            <tr class="cal-row-week" id="w2">
            </tr>
            <tr class="cal-row-week" id="w3">
            </tr>
            <tr class="cal-row-week" id="w4">
            </tr>
            <tr class="cal-row-week" id="w5">
            </tr>
            <tr class="cal-row-week" id="w6"> 
            </tr>
        </table>
    </div>
</template>

<script>

    export default {
        data(){
            return {
                targetDate: targetDate
            }
        },
        mounted(){
            this.applyHeight();
            this.refleshCalendar();
        },
        methods:{
            applyHeight: function(){
                const bodyElem = document.getElementById("bodyBase");
                bodyElem.setAttribute("style", "height: " + (window.innerHeight - document.getElementById("navHeader").clientHeight).toString() + "px;");
            },
            refleshCalendar:function(){
                const year = this.targetDate.year();
                const month = this.targetDate.month();  // 0始まり
                const firstDay = new Date(year, month, 1).getDay();
                const today = new Date();
                
                // 方針：日付-曜日=週初めの日付 なので firstDay(1日の曜日)と(row*7+1 row=0,1...)日を用いて毎週初めの日付を得る
                // new Date()引数の日付は 月の外の数字を指定すると対応する前後の月の日付を得る
                
                let dateNumber =0;
                for(let row = 0; row<6; ++row){
                    let elem = document.getElementById("w"+(row+1));
                    elem.innerHTML = "";
                    if(!(row>=4 && dateNumber<7)){  // リフレッシュ前の要素を全て消すためrowループは最後まで回す
                        for(let dayCount=0 ; dayCount<7; ++dayCount){

                            dateNumber = new Date(year, month, (row*7+1)-firstDay+dayCount).getDate();

                            let newElem = document.createElement("td");
                            if((row==0 && dateNumber>22) || (row>=4 && dateNumber<7)){  // 前後月分岐
                                if(dayCount == 0){  // 日曜日分岐
                                    newElem.classList.add("cal-cell-day", "high-c-t");
                                }else{
                                    newElem.classList.add("cal-cell-day", "dark-c-t");
                                }
                            }else{
                                if(dayCount == 0){
                                    newElem.classList.add("cal-cell-day", "high-c");
                                }else{
                                    newElem.classList.add("cal-cell-day", "dark-c");
                                }
                            }
                            if(today.getDate() === dateNumber && today.getMonth() === month && today.getFullYear() === year){
                                newElem.classList.add("today-cell");
                            }
                            newElem.textContent = dateNumber;

                            elem.appendChild(newElem);
                        }
                    }
                }
            }
        },
        watch:{
            targetDate: {
                handler: function(newValue){
                    this.refleshCalendar();
                },
                deep: true
            }
        }
    }
</script>