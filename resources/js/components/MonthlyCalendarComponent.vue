<template>
    <div class="container-xxl cal-window" style="" id="bodyBase">
        <!-- <div width="100%" height="100%" rurles="all" class="m-lg-0"> -->
            <div class="base-c small cal-headline">
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
    export default {
        data(){
            return {
                targetDate: targetDate,
                schedules: schedules,
                experiences: experiences,
                weekCount: 5,
            }
        },
        mounted(){
            this.refleshCalendar();
            this.applyHeight();
            this.dispScheExps();
            this.getScheExps();
            window.addEventListener('resize', this.applyHeight);
            $('#schedulesModal').on('hidden.bs.modal', this.dispScheExps);
        },
        methods:{
            applyHeight: function(){
                const bodyElem = document.getElementById("bodyBase");
                bodyElem.style.height = (window.innerHeight - document.getElementById("navHeader").clientHeight).toString() + "px";
                const tdsSpaceRate = 1-30/(window.innerHeight - document.getElementById("navHeader").clientHeight);
                const rowHeightPer = 100* tdsSpaceRate /this.weekCount;
                for(let row=0; row<6; ++row){
                    if(row < this.weekCount){
                        let elem = document.getElementById("w"+(row+1));
                        elem.style.height = rowHeightPer + "%";
                        continue;
                    }else{
                        let elem = document.getElementById("w"+(row+1));
                        elem.style.height = null;
                    }
                }
            },
            getScheExps: function(){
                axios.get('/api/schedules')
                    .then((res)=>{
                        schedules = res.data;
                        this.schedules = res.data;
                    });
                axios.get('/api/experiences')
                    .then((res)=>{
                        experiences = res.data;
                        this.experiences = res.data;
                    })
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

                            // カレンダーセル作成
                            let cellElem = document.createElement("div");
                            cellElem.setAttribute("id", (cellDateObject.getMonth()+1)+"/"+dateNumber);
                            if((row==0 && dateNumber>22) || (row>=4 && dateNumber<7)){  // 前後月分岐
                                if(dayCount == 0){  // 日曜日分岐
                                    cellElem.classList.add("cal-cell-day", "high-c-t");
                                }else{
                                    cellElem.classList.add("cal-cell-day", "dark-c-t");
                                }
                            }else{
                                if(dayCount == 0){
                                    cellElem.classList.add("cal-cell-day", "high-c");
                                }else{
                                    cellElem.classList.add("cal-cell-day", "dark-c");
                                }
                            }

                            // 今日装飾
                            if(today.getDate() === dateNumber && today.getMonth() === cellDateObject.getMonth() && today.getFullYear() === cellDateObject.getFullYear()){
                                cellElem.classList.add("today-cell");
                            }

                            // 日付表示div
                            let numberElem = document.createElement("div");
                            numberElem.classList.add("scroll-lock", "bg-white-c");
                            numberElem.style.padding = "5px 0px 0px";
                            numberElem.textContent = dateNumber;


                            cellElem.appendChild(numberElem);
                            cellElem.appendChild(document.createElement("div"));  // scleduleが入るボックス
                            elem.appendChild(cellElem);
                        }
                    }
                }
            },
            clearScheExps: function(){
                let scheduleElems = document.getElementsByClassName("schedule-month");
                let loopCount = scheduleElems.length;
                for(let i=0; i< loopCount; i++){
                    let s = scheduleElems[scheduleElems.length - 1];
                    if(s){
                        s.parentNode.removeChild(s);
                    }
                }
                let experienceElems = document.getElementsByClassName("experience-month");
                loopCount = experienceElems.length;
                for(let i=0; i< loopCount; i++){
                    let e = experienceElems[experienceElems.length - 1];
                    if(e){
                        e.parentNode.removeChild(e);
                    }
                }
            },
            dispScheExps: function(){
                this.clearScheExps();
                this.schedules.forEach(s => {
                    const startDate = new Date(s.start_on);
                    if(!isInMonthPage(startDate, this.targetDate.month())) return;
                    
                    const scheElem = document.createElement("button");
                    scheElem.classList.add("btn", "btn-sm", "schedule-month");
                    scheElem.textContent = s.name;

                    // 色
                    scheElem.style.background = "#" + s.color;
                    let blightness = Math.max(parseInt(s.color.slice(0,2), 16), parseInt(s.color.slice(2,4), 16), parseInt(s.color.slice(4), 16));
                    if(blightness < 8){
                        scheElem.classList.add("white-c");
                    }else{
                        scheElem.classList.add("dark-c");
                    }

                    // 詳細ウィンドウ用設定
                    // data-toggle="modal" data-target="#schedulesModal" data-schedule=id
                    scheElem.setAttribute("data-toggle", "modal");
                    scheElem.setAttribute("data-target", "#schedulesModal");
                    scheElem.setAttribute("data-schedule", s.id);

                    const parent = document.getElementById((startDate.getMonth()+1)+"/"+startDate.getDate()).lastElementChild;
                    parent.appendChild(scheElem);
                });
                this.experiences.forEach(e => {
                    const startDate = new Date(e.start_on);
                    if(!isInMonthPage(startDate, this.targetDate.month())) return;
                    
                    const expElem = document.createElement("button");
                    expElem.classList.add("btn", "btn-sm", "experience-month");
                    expElem.textContent = e.name;

                    // 色
                    expElem.style.background = "#" + e.color;
                    let blightness = Math.max(parseInt(e.color.slice(0,2), 16), parseInt(e.color.slice(2,4), 16), parseInt(e.color.slice(4), 16));
                    if(blightness < 8){
                        expElem.classList.add("white-c");
                    }else{
                        expElem.classList.add("dark-c");
                    }

                    // 詳細ウィンドウ用設定
                    // data-toggle="modal" data-target="#schedulesModal" data-schedule=id
                    expElem.setAttribute("data-toggle", "modal");
                    expElem.setAttribute("data-target", "#experiencesModal");
                    expElem.setAttribute("data-experience", e.id);

                    const parent = document.getElementById((startDate.getMonth()+1)+"/"+startDate.getDate()).lastElementChild;
                    parent.appendChild(expElem);
                });
            }
        },
        watch:{
            targetDate: {
                handler: function(newValue){
                    this.refleshCalendar();
                    this.applyHeight();
                    this.dispScheExps();
                },
                deep: true
            },
            schedules: function(newValue){
                this.dispScheExps();
            },
            experiences: function(newValue){
                this.dispScheExps();
            },
        }
    }
</script>