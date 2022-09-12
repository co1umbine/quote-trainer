<template>
    <div class="container-xxl scroll" style="" id="bodyBase">
        <table width="100%" rurles="all" class="mx-lg-0">
            <tr class="base-c small">
                <th class="cal-axis-time bg-white-c scroll-lock"></th>
                <th class="day-and-date bg-white-c scroll-lock high-c" id="sunday">SUN<br>{{sunDate}}</th>
                <th class="day-and-date bg-white-c scroll-lock" id="monday">MON<br>{{monDate}}</th>
                <th class="day-and-date bg-white-c scroll-lock" id="tuesday">TUE<br>{{tueDate}}</th>
                <th class="day-and-date bg-white-c scroll-lock" id="wednesday">WED<br>{{wedDate}}</th>
                <th class="day-and-date bg-white-c scroll-lock" id="thursday">THU<br>{{thuDate}}</th>
                <th class="day-and-date bg-white-c scroll-lock" id="friday">FRI<br>{{friDate}}</th>
                <th class="day-and-date bg-white-c scroll-lock" id="saturday">SAT<br>{{satDate}}</th>
            </tr>
            <tr class="cal-row-time" v-for="time in timeArray" :key="time" :id="time.slice(0,2)"> 
                <td class="cal-axis-time">{{time}}</td>
                <td class="cal-cell-hour" :id="time.slice(0,2)+'-0'"></td>
                <td class="cal-cell-hour" :id="time.slice(0,2)+'-1'"></td>
                <td class="cal-cell-hour" :id="time.slice(0,2)+'-2'"></td>
                <td class="cal-cell-hour" :id="time.slice(0,2)+'-3'"></td>
                <td class="cal-cell-hour" :id="time.slice(0,2)+'-4'"></td>
                <td class="cal-cell-hour" :id="time.slice(0,2)+'-5'"></td>
                <td class="cal-cell-hour" :id="time.slice(0,2)+'-6'"></td>
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
        computed:{
            timeArray: function(){
                return Array.apply(null, new Array(24)).map(function(v,i){return ("0"+i).slice(-2)+ ":00";} );
            },
            sunDate: function(){
                return new Date(this.targetDate.year(), this.targetDate.month(), this.targetDate.date()-this.targetDate.day()).getDate();
            },
            monDate: function(){
                return new Date(this.targetDate.year(), this.targetDate.month(), this.targetDate.date()-this.targetDate.day()+1).getDate();
            },
            tueDate: function(){
                return new Date(this.targetDate.year(), this.targetDate.month(), this.targetDate.date()-this.targetDate.day()+2).getDate();
            },
            wedDate: function(){
                return new Date(this.targetDate.year(), this.targetDate.month(), this.targetDate.date()-this.targetDate.day()+3).getDate();
            },
            thuDate: function(){
                return new Date(this.targetDate.year(), this.targetDate.month(), this.targetDate.date()-this.targetDate.day()+4).getDate();
            },
            friDate: function(){
                return new Date(this.targetDate.year(), this.targetDate.month(), this.targetDate.date()-this.targetDate.day()+5).getDate();
            },
            satDate: function(){
                return new Date(this.targetDate.year(), this.targetDate.month(), this.targetDate.date()-this.targetDate.day()+6).getDate();
            }
        },
        mounted(){
            this.applyHeight();
            this.currentTimeBar();
            setInterval(function(){this.currentTimeBar();}.bind(this), 120000);  // 2分おきに更新
        },
        methods:{
            applyHeight: function(){
                const bodyElem = document.getElementById("bodyBase");
                bodyElem.setAttribute("style", "height: " + (window.innerHeight - document.getElementById("navHeader").clientHeight).toString() + "px;");
            },
            currentTimeBar: function(){
                const oldLines = document.getElementsByClassName("now-line");
                for(let i=0; i<oldLines.length; ++i){
                    oldLines[i].remove();
                }

                const nowDate = new Date();

                // 表示週に今が含まれていなければ早期リターン，週初めの日付が一致するかで判定
                if(nowDate.getDate()-nowDate.getDay() != this.targetDate.date()-this.targetDate.day()) return;

                const nowElem = document.getElementById(("0"+nowDate.getHours()).slice(-2) + "-" + nowDate.getDay());
                const lineElem = document.createElement("hr");
                lineElem.classList.add("now-line");
                lineElem.setAttribute("width", "100%");
                lineElem.setAttribute("style", "top: "+((nowDate.getMinutes()/60)*100-50).toString() + "%;");
                nowElem.appendChild(lineElem);
            }
        },
        watch:{
            targetDate: {
                handler: function(newValue){
                    this.currentTimeBar();
                },
                deep: true
            }
        }
    }
</script>