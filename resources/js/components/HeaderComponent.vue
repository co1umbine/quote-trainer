<template>
    <div class="container-fluid position-sticky bg-base-c px-0 px-md-2" id="navHeader">
        <nav class="navbar p-2">
            <div>
                <button class="btn navbar-toggler svg-key-c py-2" @click="" type="button">
                    
                    <svg width="27" height="21" alt="Menu" viewBox="0 0 36 28"  xmlns="http://www.w3.org/2000/svg">
                    <rect width="36" height="6" />
                    <rect y="11" width="36" height="6" />
                    <rect y="22" width="36" height="6" />
                    </svg>
                </button>
                <span class="navbar-brand mb-0 white-c align-middle d-none d-md-inline">QUOTE trainer</span>
                <span class="navbar-text h2 bold mb-0 white-c align-middle mr-2">{{ year }}</span>
                <button type="button" id="m-w" onclick="location.href='#'" class="btn btn-lg key-c ol-key-c align-items-center" style="padding: 0.1rem 0.4rem; border: 2px solid;">月</button>
            </div>

            <div>
                <button class="btn svg-key-c" type="button" @click="decrement()">
                    <svg width="20" height="33.75" viewBox="0 0 32 54" stroke="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.860687" y="26.9055" width="5.77286" height="38.0678" transform="rotate(-45 0.860687 26.9055)"/>
                    <rect x="27.7787" width="5.77286" height="38.0069" transform="rotate(45 27.7787 0)" />
                    </svg>
                </button>
                <span class="navbar-text h2 mb-0 white-c align-middle" id="month">{{ month }}</span>
                <button class="btn svg-key-c" type="button" @click="increment()">
                    <svg width="20" height="33.75" viewBox="0 0 32 54" stroke="none" transform="rotate(180)" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.860687" y="26.9055" width="5.77286" height="38.0678" transform="rotate(-45 0.860687 26.9055)"/>
                    <rect x="27.7787" width="5.77286" height="38.0069" transform="rotate(45 27.7787 0)" />
                    </svg>
                </button>
            </div>

            <div>
                <button id="plusBtn" class="btn svg-key-c" type="button" data-toggle="modal" data-target="#schedulesModal">
                    <svg width="34.5" height="34.5" viewBox="0 0 46 46"  xmlns="http://www.w3.org/2000/svg">
                    <rect x="19.7143" width="6.57143" height="46" />
                    <rect x="46" y="19.7143" width="6.57143" height="46" transform="rotate(90 46 19.7143)" />
                    </svg>
                </button>
                <button class="btn svg-key-c-no-fill d-none d-md-inline" type="button">
                    <svg width="46" height="46" viewBox="0 0 61 61" fill="none"  xmlns="http://www.w3.org/2000/svg">
                    <path d="M50.0872 27.9218C46.1188 33.8772 38.0713 35.4899 32.1124 31.5228C26.1535 27.5557 24.5408 19.5118 28.5092 13.5564C32.4777 7.60098 40.5252 5.9883 46.484 9.95538C52.4429 13.9225 54.0556 21.9664 50.0872 27.9218Z" stroke-width="4"/>
                    <path d="M47.6543 47.4436C43.6859 53.399 35.6384 55.0117 29.6795 51.0446C23.7206 47.0775 22.1079 39.0336 26.0764 33.0782C30.0448 27.1228 38.0923 25.5101 44.0512 29.4772C50.01 33.4443 51.6227 41.4882 47.6543 47.4436Z" stroke-width="4"/>
                    <path d="M31.5376 36.7141C27.5692 42.6695 19.5217 44.2822 13.5628 40.3151C7.60397 36.348 5.99125 28.3041 9.95969 22.3487C13.9281 16.3933 21.9756 14.7806 27.9345 18.7477C33.8934 22.7148 35.5061 30.7587 31.5376 36.7141Z" stroke-width="4"/>
                    <line x1="2.23886" y1="1.75958" x2="2.23886" y2="59.8257" stroke-width="4"/>
                    <line x1="0.238861" y1="58.126" x2="58.3318" y2="58.126" stroke-width="4"/>
                    </svg>
                </button>
            </div>
        </nav>
        
    </div>
</template>

<script>

    export default {    
        data(){
            return {
                targetDate: targetDate,
                schedules: schedules,
            }
        },
        computed:{
            year: function(){ return this.targetDate.year()},
            month: function(){ return (this.targetDate.month()+1).toString() + "月"},
        },

        mounted(){
            this.calendarType();
            document.addEventListener('keydown', this.onKeyDown);
            $('#schedulesModal').on('show.bs.modal', function (event) {
                keyAcceptance =  false;
            });
            $('#schedulesModal').on('hidden.bs.modal', function (event) {
                keyAcceptance =  true;
            });
        },
        beforeDestroy() {
            document.removeEventListener('keydown', this.onKeyDown)
        },

        methods: {
            calendarType: function(){
                const path = location.pathname;
                const m_w = document.getElementById("m-w");

                const monthPath = "/calendar/month";
                const weekPath = "/calendar/week";
                const onClick = "location.href="
                // const spinerElement = document.createElement('span');

                if(path === monthPath){
                    m_w.textContent = "月";
                    m_w.setAttribute('onclick', onClick + "'" +weekPath+"'");
                }else if(path === weekPath){
                    m_w.textContent = "週";
                    m_w.setAttribute('onclick', onClick + "'" +monthPath+"'");
                }
            },

            decrement: function(){
                const path = location.pathname;
                const monthPath = "/calendar/month";
                const weekPath = "/calendar/week";
                if(path === monthPath){
                    const prevMonth = this.targetDate.dateObject.getMonth();
                    
                    const dstDate = new Date(this.targetDate.dateObject.setMonth(this.targetDate.dateObject.getMonth()-1));

                    if(prevMonth-1 !== dstDate.getMonth() && prevMonth !== 0){
                        dstDate.setMonth(prevMonth, 0);
                    }
                    targetDate.dateObject = dstDate;
                }else if(path === weekPath){
                    targetDate.dateObject = new Date(this.targetDate.dateObject.getFullYear(), this.targetDate.dateObject.getMonth(), this.targetDate.dateObject.getDate()-7);
                }
            },

            increment: function(){
                const path = location.pathname;
                const monthPath = "/calendar/month";
                const weekPath = "/calendar/week";
                if(path === monthPath){
                    const prevMonth = this.targetDate.dateObject.getMonth();
                    let dstDate = new Date(this.targetDate.dateObject.setMonth(this.targetDate.dateObject.getMonth()+1));

                    if(prevMonth+1 !== dstDate.getMonth() && prevMonth !== 11){  // 31日など翌月に対応する日がない場合スキップされる現象への対応
                        dstDate.setDate(0);
                    }
                    targetDate.dateObject = dstDate;
                }else if(path === weekPath){
                    targetDate.dateObject = new Date(this.targetDate.dateObject.getFullYear(), this.targetDate.dateObject.getMonth(), this.targetDate.dateObject.getDate()+7);
                }
            },

            menuDraw: function(){
                document.getElementById("drawer").classList.toggle("active");
            },

            onKeyDown: function(event){
                if(!keyAcceptance) return;

                if(event.key === "m" || event.key === "M"){
                    window.location.href = "/calendar/month";
                }else if(event.key === "w" || event.key === "W"){
                    window.location.href = "/calendar/week";
                }else if(event.key === "t" || event.key === "T"){
                    targetDate.dateObject = new Date();
                }else if(event.key === "ArrowLeft" || event.key === "ArrowUp"){
                    this.decrement();
                }else if(event.key === "ArrowRight" || event.key === "ArrowDown"){
                    this.increment();
                }
            },
        },
    }
</script>