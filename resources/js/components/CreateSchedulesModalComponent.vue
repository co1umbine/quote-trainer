<template>
    <div class="modal" id="schedulesModal" tabindex="-1" aria-labelledby="RegisterSchedule" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="RegisterSchedule">{{viewOnly ? "スケジュール詳細" : updateMode? "スケジュール編集" :"スケジュール新規作成"}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <div aria-hidden="true">&times;</div>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="scheduleName" class="form-label">スケジュール名</label>
                        <input v-show="!viewOnly" type="text" class="form-control" id="scheduleName" v-model="nameText">
                        <div v-show="viewOnly">{{nameText}}</div>
                    </div>

                    <div class="mb-3">
                        <div>
                            <label for="tags" class="form-label">タグ</label>
                            <input v-show="!viewOnly" name="tags" type="text" list="tags_list" id="tagsInput" class="form-control" placeholder="Type to search...">
                            <datalist v-show="!viewOnly" id="tags_list">
                                リストから選択
                                <select name="tags">
                                    <option v-for="tag in tags" :key="tag">{{ tag }}</option>
                                </select>
                            </datalist>
                        </div>
                        <div style="height: 45px;">
                            <button v-for="tag in selectedTags" :key="tag" type="button" @click="deselectTag(tag)" class="btn btn-sm my-2 ml-2  dark-c bg-themeblue-c btn-rounded delete-self-btn">{{ tag }}</button>
                            <!-- <button type="button" @click="() => {}" class="btn btn-sm my-2 ml-2  dark-c bg-themeblue-c btn-rounded delete-self-btn">指定中のタグ</button> -->
                        </div>
                    </div>

                    <div class="mb-3">
                        <!--<label for="scheduleColor" class="form-label">スケジュール固有色</label> -->
                        <input :disabled="viewOnly" type="color" v-model="selectedColor" class="form-control form-control-color" id="scheduleColor" aria-describedby="colorHelp"/>
                        <button v-show="!viewOnly" type="button" @click="() => {selectedColor = '#a7cbd9'}" class="btn btn-sm bg-themeblue-c color-select-btn"></button>
                        <button v-show="!viewOnly" type="button" @click="() => {selectedColor = '#b5a7d9'}" class="btn btn-sm bg-themepurple-c color-select-btn"></button>
                        <button v-show="!viewOnly" type="button" @click="() => {selectedColor = '#d9a7cb'}" class="btn btn-sm bg-themepink-c color-select-btn"></button>
                        <button v-show="!viewOnly" type="button" @click="() => {selectedColor = '#d9b5a7'}" class="btn btn-sm bg-themeorange-c color-select-btn"></button>
                        <button v-show="!viewOnly" type="button" @click="() => {selectedColor = '#cbd9a7'}" class="btn btn-sm bg-themeyellow-c color-select-btn"></button>
                        <button v-show="!viewOnly" type="button" @click="() => {selectedColor = '#a7d9b5'}" class="btn btn-sm bg-themegreen-c color-select-btn"></button>
                    </div>

                    <div class="mb-3">
                        
                        <label>見積もり期間： {{ quote }}</label>
                        <div class="form-inline">
                        <input :disabled="viewOnly" type="datetime-local" id="start-on"
                            name="start-on" v-model="startOn"
                            class="form-control">
                            
                        <!-- <label for="start-on"></label> -->
                        <span>&nbsp;～&nbsp;</span>

                        <input :disabled="viewOnly" type="datetime-local" id="end-on"
                            name="end-on" v-model="endOn"
                            :min="startOn" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="scheduleNote" class="form-label">備考</label>
                        <textarea v-show="!this.viewOnly" type="text" class="form-control" id="scheduleNote" v-model="noteText"></textarea>
                        <div v-show="this.viewOnly">{{ noteText }}</div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <!-- <button v-if="this.viewOnly" type="button" @click="" class="btn white-c bg-base-c">削除</button> -->
                    <button v-show="!this.viewOnly" type="button" data-dismiss="modal" aria-label="Close" class="btn ol-dark-c dark-c">キャンセル</button>
                    <button v-show="this.viewOnly" type="button" @click="()=>{this.viewOnly = false;}" class="btn white-c bg-base-c">編集</button>
                    <button v-show="!this.viewOnly" type="button" @click="()=>{this.updateMode ? tentativeUpdate() : tentative()}" class="btn white-c bg-base-c">続行</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import qs from 'qs';
function paramsSerializer(params) {
  return qs.stringify(params);
}

    export default {  
        data(){
            return {
                nameText: "",
                targetDate: targetDate,
                tags: ["タグの例1", "タグの例2", "タグの例3"],
                selectedTags: [],
                selectedColor: "#A7CBD9",
                startOn: this.dispDateCeilMinute(targetDate.dateObject) + "T" + this.dispTimeCeilMinute(targetDate.dateObject),
                endOn: this.dispDateCeilMinute(targetDate.dateObject) + "T" + this.dispTimeCeilMinute(new Date(targetDate.dateObject.setHours(targetDate.dateObject.getHours() + 1))),
                noteText: "",
                viewOnly: false,
                updateMode: false,
                srcSchedule: null,
            }
        },
        computed:{
            quote: function(){
                if(this.endOn !== ""){
                    const endTime = new Date(this.endOn).getTime();
                    const startTime = new Date(this.startOn).getTime();
                    const quote = new Date(endTime - startTime);

                    if(endTime <= startTime){
                        return "";
                    }
                    if(quote.getTime() > 8.64e+7){
                        return this.dispPeriodDate(quote) +"日 "+ this.dispPeriodTime(quote);
                    }else{
                        return this.dispPeriodTime(quote);
                    }
                }
            }
        },
        mounted(){
            this.tagApply();
            // 立ち上がるときの処理
            $('#schedulesModal').on('show.bs.modal', (event) => {
                // 入力情報をクリア
                this.refresh();
                
                if(event.relatedTarget.id === "plusBtn") return;
                this.viewOnly = true;
                this.updateMode = true;

                // ボタンを取得
                const button = $(event.relatedTarget);

                // data-***の部分を取得
                const scheduleId = button.data("schedule");
                this.srcSchedule = schedules.find(s => s.id === scheduleId);
                if(typeof this.srcSchedule === "undefined") return;
                
                // データ表示
                this.nameText = this.srcSchedule.name;
                this.selectedColor = "#"+this.srcSchedule.color;
                const sd = new Date(this.srcSchedule.start_on);
                this.startOn = this.dispDateCeilMinute(sd) + "T" + this.dispTimeCeilMinute(sd);
                const ed = new Date(sd.getTime() + this.srcSchedule.quote);
                this.endOn = this.dispDateCeilMinute(ed) + "T" + this.dispTimeCeilMinute(ed);
                this.noteText = this.srcSchedule.note;

                // TODO selectedTags
            });
        },
        methods:{
            tagApply: function(){
                if(document.getElementById("tagsInput") === null) return;
                document.getElementById("tagsInput").addEventListener("input", () => {
                    const elem = $("#tagsInput");
                    const val = elem.value;
                    elem.value = "";
                    // TODO タグを新規に追加する機能？
                    if(!this.selectedTags.includes(val)){
                        this.selectedTags.push(val);
                    }
                });
            },
            refresh: function(){
                console.log("refresh");
                this.selectedTags = [];
                this.nameText = "";
                if(document.getElementById("tagsInput") !== null) document.getElementById("tagsInput").value = "";
                this.noteText = "";
                this.selectedColor = "#A7CBD9";
                const date = new Date();
                this.startOn = this.dispDateCeilMinute(date) + "T" + this.dispTimeCeilMinute(date);
                this.endOn = this.dispDateCeilMinute(date) + "T" + this.dispTimeCeilMinute(new Date(date.setHours(date.getHours() + 1)));
                
                this.viewOnly = false;
                this.updateMode = false;
                this.srcSchedule = null;
            },
            deselectTag: function(tag){
                if(!this.selectedTags.includes(tag)) return;
                this.selectedTags = this.selectedTags.filter(n => n !== tag);
            },
            tentative: function(){
                if(this.nameText === "") {
                    this.nameText = "（スケジュール名なし）";
                }

                const startOnDate = new Date(this.startOn);
                const timezone = startOnDate.getTimezoneOffset() * -1 >=0 ? "+"+(startOnDate.getTimezoneOffset()/60 * -1) : (startOnDate.getTimezoneOffset()/60 * -1);
                const quote = new Date(this.endOn).getTime() - new Date(this.startOn).getTime();

                const schedule= {
                    user_id: 1,
                    name: this.nameText,
                    color: this.selectedColor.slice(-6),
                    start_on: this.dispDate(startOnDate) +" "+ this.dispTime(startOnDate) +" "+ timezone,
                    quote: quote,
                    note: this.noteText,
                }
                axios.post("/api/schedules", schedule)
                    .then((res) => {
                    schedules.push(schedule);
                        $("#schedulesModal").modal("hide");
                    })
            },
            tentativeUpdate: function(){
                if(this.nameText === "") {
                    this.nameText = "（スケジュール名なし）";
                }

                const startOnDate = new Date(this.startOn);
                const timezone = startOnDate.getTimezoneOffset() * -1 >=0 ? "+"+(startOnDate.getTimezoneOffset()/60 * -1) : (startOnDate.getTimezoneOffset()/60 * -1);
                const quote = new Date(this.endOn).getTime() - new Date(this.startOn).getTime();

                const schedule= {
                    user_id: 1,
                    name: this.nameText,
                    color: this.selectedColor.slice(-6),
                    start_on: this.dispDate(startOnDate) +" "+ this.dispTime(startOnDate) +" "+ timezone,
                    quote: quote,
                    note: this.noteText,
                }
                const index = schedules.indexOf(this.srcSchedule);
                if(typeof index === -1) return;  // TODO return でいいのか

                axios.put('/api/schedules/' + this.srcSchedule.id, schedule)
                    .then((res) => {
                        schedules.splice(index, 1, schedule);
                        $("#createSchedulesModal").modal("hide");
                    })

            },
            switchToModification: function(){
                this.viewOnly = false;
            },
            switchToView: function(){
                this.viewOnly = true;
            },
            dispDate: function(date){
                const YYYY = date.getFullYear();
                const MM = ("00" + (date.getMonth()+1)).slice(-2);
                const DD = ("00" + date.getDate()).slice(-2);
                return YYYY + "-" + MM + "-" + DD;
            },
            dispTime: function(date){
                const hh = ("00" + date.getHours()).slice(-2);
                const mm = ("00" + date.getMinutes()).slice(-2);
                const ss = ("00" + date.getSeconds()).slice(-2);

                return hh + ":" + mm + ":" + ss;
            },
            dispDateCeilMinute: function(date){
                const mDate = new Date(date);
                if(mDate.getMinutes() > 50){
                    mDate.setMinutes(0);
                    mDate.setHours(mDate.getHours()+1);
                }
                const YYYY = mDate.getFullYear();
                const MM = ("00" + (mDate.getMonth()+1)).slice(-2);
                const DD = ("00" + mDate.getDate()).slice(-2);
                return YYYY + "-" + MM + "-" + DD;
            },
            dispTimeCeilMinute: function(date){
                const mDate = new Date(date);
                if(mDate.getMinutes() > 50){
                    mDate.setMinutes(0);
                    mDate.setHours(mDate.getHours()+1);
                }
                const hh = ("00" + mDate.getHours()).slice(-2);
                const mm = ("00" + (Math.ceil(mDate.getMinutes()/10)*10)).slice(-2);
                return hh + ":" + mm;
            },
            dispPeriodDate: function(date){
                const ms = date.getTime();
                const DD = Math.floor(ms/ 8.64e+7);
                return DD;
            },
            dispPeriodTime: function(date){
                const hh = ("00" + date.getUTCHours()).slice(-2);
                const mm = ("00" + date.getUTCMinutes()).slice(-2);

                return hh + ":" + mm;
            }
        }
    }
</script>