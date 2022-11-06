<template>
    <div class="container-xxl scroll cal-window" style="" id="bodyBase">
        <!-- <table width="100%" rurles="all" class="mx-lg-0"> -->
        <div class="base-c small scroll-lock cal-headline">
            <div class="cal-axis-time bg-white-c"></div>
            <div class="day-and-date bg-white-c high-c" id="sunday">SUN<br>{{sunDate}}</div>
            <div class="day-and-date bg-white-c" id="monday">MON<br>{{monDate}}</div>
            <div class="day-and-date bg-white-c" id="tuesday">TUE<br>{{tueDate}}</div>
            <div class="day-and-date bg-white-c" id="wednesday">WED<br>{{wedDate}}</div>
            <div class="day-and-date bg-white-c" id="thursday">THU<br>{{thuDate}}</div>
            <div class="day-and-date bg-white-c" id="friday">FRI<br>{{friDate}}</div>
            <div class="day-and-date bg-white-c" id="saturday">SAT<br>{{satDate}}</div>
        </div>
        <div class="cal-row-time" v-for="time in timeArray" :key="time" :id="time.slice(0,2)"> 
            <div class="cal-axis-time">{{time}}</div>
            <div class="cal-cell-hour" :id="time.slice(0,2)+'-0'"></div>
            <div class="cal-cell-hour" :id="time.slice(0,2)+'-1'"></div>
            <div class="cal-cell-hour" :id="time.slice(0,2)+'-2'"></div>
            <div class="cal-cell-hour" :id="time.slice(0,2)+'-3'"></div>
            <div class="cal-cell-hour" :id="time.slice(0,2)+'-4'"></div>
            <div class="cal-cell-hour" :id="time.slice(0,2)+'-5'"></div>
            <div class="cal-cell-hour" :id="time.slice(0,2)+'-6'"></div>
        </div>
        <!-- </table> -->
    </div>
</template>

<script>

    export default {
        data(){
            return {
                targetDate: targetDate,
                schedules: schedules,
                experiences: experiences,
                scheExpsCount: [0, 0, 0, 0, 0, 0, 0],
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
            this.dispScheExps();
            this.getScheExps();
            setInterval(function(){this.currentTimeBar();}.bind(this), 120000);  // 2分おきに更新
            window.addEventListener('resize', this.applyHeight());
            $('#schedulesModal').on('hidden.bs.modal', this.dispScheExps);
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
                    });
            },
            clearScheExps: function(){
                this.scheExpsCount = [0, 0, 0, 0, 0, 0, 0];
                let scheduleElems = document.getElementsByClassName("schedule-week");
                let loopCount = scheduleElems.length;
                for(let i=0; i< loopCount; i++){
                    let s = scheduleElems[scheduleElems.length - 1];
                    if(s){
                        s.parentNode.removeChild(s);
                    }
                }
                let experienceElems = document.getElementsByClassName("schedule-week");
                loopCount = experienceElems.length;
                for(let i=0; i< loopCount; i++){
                    let s = experienceElems[experienceElems.length - 1];
                    if(s){
                        s.parentNode.removeChild(s);
                    }
                }
            },
            dispScheExps: function(){
                this.clearScheExps();
                this.schedules.forEach(s => {
                    const startDate = new Date(s.start_on);
                    const quote = new Date(s.quote);
                    const endDate = new Date(startDate);
                    endDate.setHours(endDate.getHours()+Math.floor(quote.getTime()/3600000), endDate.getMinutes()+quote.getMinutes());

                    // 同じ週になければ終了次の予定へ，週初めの日付が一致するか判定
                    if(!isInSameWeek(startDate, this.targetDate.dateObject) && !isInSameWeek(endDate, this.targetDate.dateObject) && !(startDate <= this.targetDate.dateObject && this.targetDate.dateObject <= endDate)) return;

                    // スケジュールオブジェクト
                    const scheElem = document.createElement("button");
                    scheElem.classList.add("btn", "btn-sm", "schedule-week");
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


                    // スケジュールタイトル
                    const spanElem = document.createElement("span");
                    spanElem.textContent = s.name;
                    spanElem.classList.add("top-text");
                    scheElem.appendChild(spanElem);
                    
                    // サイズ指定 生成
                    let dstDate, dstStartTime;  // 複数日に渡るスケジュールでは 予定ボタンの始まりは 元の予定開始時間 → 次の日の0：00 → 次の日の0:00となる
                    const baseZIndex = 10;
                    if(isInSameWeek(startDate, this.targetDate.dateObject)){
                        dstStartTime = new Date(startDate);
                    }else{ 
                        dstStartTime = new Date(this.targetDate.dateObject.getFullYear(), this.targetDate.dateObject.getMonth(), this.targetDate.dateObject.getDate()-this.targetDate.dateObject.getDay());
                    }
                    dstDate = new Date(dstStartTime);
                    dstDate.setHours(0, 0, 0, 0);
                    while(dstDate <= endDate){
                        if(isSameDate(dstDate, endDate)){  // 予定が現在見てる日で終了する場合
                            scheElem.style.top = (dstStartTime.getMinutes()/60*100).toString() +"%";
                            scheElem.style.height = (((endDate.getHours()-dstStartTime.getHours())*60 + endDate.getMinutes()-dstStartTime.getMinutes())/60*100*1.04).toString() + "%";
                            
                            // 重なり調整
                            scheElem.style.zIndex = baseZIndex + this.scheExpsCount[dstDate.getDay()];
                            this.scheExpsCount[dstDate.getDay()] += 1;

                            const parent = document.getElementById(("0"+dstStartTime.getHours()).slice(-2) + "-" +dstStartTime.getDay());
                            parent.appendChild(scheElem.cloneNode(true));
                            break;
                        }else{
                            scheElem.style.top = (dstStartTime.getMinutes()/60*100).toString() +"%";
                            scheElem.style.height = (((23-dstStartTime.getHours())*60 + (60-dstStartTime.getMinutes()))/60*100*1.04).toString() + "%";
                            scheElem.style.right = "0%";

                            // 重なり調整
                            scheElem.style.zIndex = baseZIndex + this.scheExpsCount[dstDate.getDay()];
                            scheElem.style.width = 96/(this.scheExpsCount[dstDate.getDay()]+1).toString() + "%";
                            this.scheExpsCount[dstDate.getDay()] += 1;

                            const parent = document.getElementById(("0"+dstStartTime.getHours()).slice(-2) + "-" +dstStartTime.getDay());
                            parent.appendChild(scheElem.cloneNode(true));

                            dstDate.setDate(dstDate.getDate() + 1);
                            if(dstDate.getDay() === 0) return;
                            dstStartTime = dstDate;
                        }
                    }

                });
                this.experiences.forEach(e => {
                    const startDate = new Date(e.start_on);
                    const quote = new Date(e.quote);
                    const endDate = new Date(startDate);
                    endDate.setHours(endDate.getHours()+Math.floor(quote.getTime()/3600000), endDate.getMinutes()+quote.getMinutes());

                    // 同じ週になければ終了次の予定へ，週初めの日付が一致するか判定
                    if(!isInSameWeek(startDate, this.targetDate.dateObject) && !isInSameWeek(endDate, this.targetDate.dateObject) && !(startDate <= this.targetDate.dateObject && this.targetDate.dateObject <= endDate)) return;

                    // スケジュールオブジェクト
                    const expElem = document.createElement("button");
                    expElem.classList.add("btn", "btn-sm", "experience-week");
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


                    // スケジュールタイトル
                    const spanElem = document.createElement("span");
                    spanElem.textContent = e.name;
                    spanElem.classList.add("top-text");
                    expElem.appendChild(spanElem);
                    
                    // サイズ指定 生成
                    let dstDate, dstStartTime;  // 複数日に渡るスケジュールでは 予定ボタンの始まりは 元の予定開始時間 → 次の日の0：00 → 次の日の0:00となる
                    const baseZIndex = 10;
                    if(isInSameWeek(startDate, this.targetDate.dateObject)){
                        dstStartTime = new Date(startDate);
                    }else{ 
                        dstStartTime = new Date(this.targetDate.dateObject.getFullYear(), this.targetDate.dateObject.getMonth(), this.targetDate.dateObject.getDate()-this.targetDate.dateObject.getDay());
                    }
                    dstDate = new Date(dstStartTime);
                    dstDate.setHours(0, 0, 0, 0);
                    while(dstDate <= endDate){
                        if(isSameDate(dstDate, endDate)){  // 予定が現在見てる日で終了する場合
                            expElem.style.top = (dstStartTime.getMinutes()/60*100).toString() +"%";
                            expElem.style.height = (((endDate.getHours()-dstStartTime.getHours())*60 + endDate.getMinutes()-dstStartTime.getMinutes())/60*100*1.04).toString() + "%";
                            
                            // 重なり調整
                            expElem.style.zIndex = baseZIndex + this.scheExpsCount[dstDate.getDay()];
                            this.scheExpsCount[dstDate.getDay()] += 1;

                            const parent = document.getElementById(("0"+dstStartTime.getHours()).slice(-2) + "-" +dstStartTime.getDay());
                            parent.appendChild(expElem.cloneNode(true));
                            break;
                        }else{
                            expElem.style.top = (dstStartTime.getMinutes()/60*100).toString() +"%";
                            expElem.style.height = (((23-dstStartTime.getHours())*60 + (60-dstStartTime.getMinutes()))/60*100*1.04).toString() + "%";
                            expElem.style.right = "0%";

                            // 重なり調整
                            expElem.style.zIndex = baseZIndex + this.scheExpsCount[dstDate.getDay()];
                            expElem.style.width = 96/(this.scheExpsCount[dstDate.getDay()]+1).toString() + "%";
                            this.scheExpsCount[dstDate.getDay()] += 1;

                            const parent = document.getElementById(("0"+dstStartTime.getHours()).slice(-2) + "-" +dstStartTime.getDay());
                            parent.appendChild(expElem.cloneNode(true));

                            dstDate.setDate(dstDate.getDate() + 1);
                            if(dstDate.getDay() === 0) return;
                            dstStartTime = dstDate;
                        }
                    }

                });
            }
        },
        watch:{
            targetDate: {
                handler: function(newValue){
                    this.currentTimeBar();
                    this.dispScheExps();
                },
                deep: true
            },
            schedules: function(newValue){
                this.dispScheExps();
            },
            experiences: function(newValue){
                this.dispScheExps();
            }
        }
    }
</script>