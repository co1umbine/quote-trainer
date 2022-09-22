<template>
    <div class="container-xxl cal-window" style="" id="bodyBase">
        <!-- <div width="100%" height="100%" rurles="all" class="m-lg-0"> -->
            <div class="base-c small" style="display: flex;">
                <div class="day-of-week high-c">SUN</div>
                <div class="day-of-week">MON</div>
                <div class="day-of-week">TUE</div>
                <div class="day-of-week">WED</div>
                <div class="day-of-week">THU</div>
                <div class="day-of-week">FRI</div>
                <div class="day-of-week">SAT</div>
            </div>
            <div class="cal-row-week" id="w1"></div>
            <div class="cal-row-week" id="w2"></div>
            <div class="cal-row-week" id="w3"></div>
            <div class="cal-row-week" id="w4"></div>
            <div class="cal-row-week" id="w5"></div>
            <div class="cal-row-week" id="w6"></div>
            
        <!-- </div> -->
    </div>
</template>

<script>
import axios from 'axios';
    export default {
        data(){
            return {
                targetDate: targetDate,
                schedules: [],
                weekCount: 5,
            }
        },
        mounted(){
            this.refleshCalendar();
            this.applyHeight();
            this.getSchedules();
            window.addEventListener('resize', this.applyHeight());
        },
        methods:{
            applyHeight: function(){
                console.log("resize()")
                const bodyElem = document.getElementById("bodyBase");
                bodyElem.style.height = (window.innerHeight - document.getElementById("navHeader").clientHeight).toString() + "px";
                const tdsSpaceRate = 1-30/(window.innerHeight - document.getElementById("navHeader").clientHeight);
                const rowHeightPer = 100* tdsSpaceRate /this.weekCount;
                for(let row=0; row<this.weekCount; ++row){
                    let elem = document.getElementById("w"+(row+1));
                    elem.style.height = rowHeightPer + "%";
                }
            },
            refleshCalendar: function(){
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
                        this.weekCount = row+1;
                        for(let dayCount=0 ; dayCount<7; ++dayCount){
                            const cellDateObject = new Date(year, month, (row*7+1)-firstDay+dayCount)
                            dateNumber = cellDateObject.getDate();

                            let newElem = document.createElement("div");
                            newElem.setAttribute("id", (cellDateObject.getMonth()+1)+"/"+dateNumber);
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
                            let numberElem = document.createElement("div");
                            numberElem.classList.add("scroll-lock", "bg-white-c");
                            numberElem.style.padding = "5px 0px 0px";

                            numberElem.textContent = dateNumber;

                            newElem.appendChild(numberElem);

                            newElem.appendChild(document.createElement("div"));

                            elem.appendChild(newElem);
                        }
                    }
                }
            },
            getSchedules: function(){
                axios.get('/api/schedules')
                    .then((res)=>{
                        this.schedules = res.data;
                    });
            },
            applySchedules: function(){
                this.schedules.forEach(s => {
                    const startDate = new Date(s.start_on);
                    if(startDate.getMonth() !== this.targetDate.month()) return;
                    
                    const scheElem = document.createElement("button");
                    scheElem.classList.add("btn", "btn-sm", "schedule");
                    scheElem.textContent = s.name;
                    scheElem.style.background = "#" + s.color;
                    let blightness = Math.max(parseInt(s.color.slice(0,2), 16), parseInt(s.color.slice(2,4), 16), parseInt(s.color.slice(4), 16));
                    if(blightness < 8){
                        scheElem.classList.add("white-c");
                    }else{
                        scheElem.classList.add("dark-c");
                    }

                    const parent = document.getElementById((startDate.getMonth()+1)+"/"+startDate.getDate()).lastElementChild;
                    parent.appendChild(scheElem);
                });
            }
        },
        watch:{
            targetDate: {
                handler: function(newValue){
                    this.refleshCalendar();
                    this.applySchedules();
                },
                deep: true
            },
            schedules: function(newValue){
                this.applySchedules();
            }
        }
    }
</script>