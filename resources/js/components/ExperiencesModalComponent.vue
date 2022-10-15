<template>
    <div class="modal" id="experiencesModal" tabindex="-1" aria-labelledby="RegisterExperience" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="RegisterExperience">{{viewOnly ? "経験詳細" : "経験編集"}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <div aria-hidden="true">&times;</div>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="experienceName" class="form-label">経験名</label>
                        <div>{{nameText}}</div>
                    </div>

                    <div class="mb-3">
                        <div>
                            <label for="tags" class="form-label">タグ</label>
                            <div style="height: 45px;">
                                <button v-for="tag in selectedTags" :key="tag.id" type="button" @click="deselectTag(tag)" :style="'background-color: #'+ tag.color +';' + (viewOnly ? 'pointer-events: none;':'')" class="btn btn-sm my-2 ml-2 dark-c btn-rounded delete-self-btn">{{ tag.name }}</button>
                                <!-- <button type="button" @click="() => {}" class="btn btn-sm my-2 ml-2  dark-c bg-themeblue-c btn-rounded delete-self-btn">指定中のタグ</button> -->
                            </div>
                            <select v-show="!viewOnly" name="tags" id="eTagsInput" class="form-control">
                                <option hidden>選択してください</option>
                                <option v-for="tag in tags" :disabled="selectedTags.findIndex(t=>t.id === tag.id) !== -1" :key="tag.id" :value="tag.id">{{ tag.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <input :disabled="viewOnly" type="color" v-model="selectedColor" class="form-control form-control-color" id="experienceColor" aria-describedby="colorHelp"/>
                        <button v-show="!viewOnly" type="button" @click="() => {selectedColor = '#a7cbd9'}" class="btn btn-sm bg-themeblue-c color-select-btn"></button>
                        <button v-show="!viewOnly" type="button" @click="() => {selectedColor = '#b5a7d9'}" class="btn btn-sm bg-themepurple-c color-select-btn"></button>
                        <button v-show="!viewOnly" type="button" @click="() => {selectedColor = '#d9a7cb'}" class="btn btn-sm bg-themepink-c color-select-btn"></button>
                        <button v-show="!viewOnly" type="button" @click="() => {selectedColor = '#d9b5a7'}" class="btn btn-sm bg-themeorange-c color-select-btn"></button>
                        <button v-show="!viewOnly" type="button" @click="() => {selectedColor = '#cbd9a7'}" class="btn btn-sm bg-themeyellow-c color-select-btn"></button>
                        <button v-show="!viewOnly" type="button" @click="() => {selectedColor = '#a7d9b5'}" class="btn btn-sm bg-themegreen-c color-select-btn"></button>
                    </div>

                    <div class="mb-3">
                        <div><span class="small">見積もり期間： </span>{{ quote }} <span  class="small">に対して</span></div>
                        <label>かかった期間： {{ period }}</label>
                        <div class="form-inline">
                            <input disabled type="datetime-local"
                                name="start-on" v-model="startOn"
                                class="form-control">
                                
                                
                            <span>&nbsp;～&nbsp;</span>

                            <input :disabled="viewOnly" type="datetime-local"
                                name="end-on" v-model="endOn"
                                :min="startOn" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>体感効率 {{efficiency}}</label>
                        <div class="input-group form-inline">
                            悪い <input :disabled="viewOnly" type="range" name="efficiency" min="-2" max="2" class="form-control" v-model="efficiency"> 良い
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="experienceNote" class="form-label">備考</label>
                        <textarea v-show="!this.viewOnly" type="text" class="form-control" id="experienceNote" v-model="noteText"></textarea>
                        <div v-show="this.viewOnly">{{ noteText }}</div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <!-- <button v-if="this.viewOnly" type="button" @click="" class="btn white-c bg-base-c">削除</button> -->
                    
                    <button type="button" @click="deleteExperience()" class="btn ol-high-c high-c">削除</button>
                    <button v-show="!this.viewOnly" type="button" @click="()=>{this.viewOnly = true; refresh();}" class="btn ol-dark-c dark-c">キャンセル</button>
                    <button v-show="this.viewOnly" type="button" @click="()=>{this.viewOnly = false;}" class="btn white-c bg-base-c">編集</button>
                    <button v-show="!this.viewOnly" type="button" @click="()=>{tentativeUpdate()}" class="btn white-c bg-base-c">続行</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import qs from 'qs';
// HTTPクエリ文エンコードのため
function paramsSerializer(params) {
  return qs.stringify(params);
}

    export default {  
        data(){
            return {
                nameText: "",
                targetDate: targetDate,
                tags: [],
                selectedTags: [],
                selectedColor: "#A7CBD9",
                quote: "",
                startOn: this.dispDateCeilMinute(targetDate.dateObject) + "T" + this.dispTimeCeilMinute(targetDate.dateObject),
                endOn: this.dispDateCeilMinute(targetDate.dateObject) + "T" + this.dispTimeCeilMinute(new Date(targetDate.dateObject.setHours(targetDate.dateObject.getHours() + 1))),
                noteText: "",
                viewOnly: false,
                srcExperience: null,
                efficiency: 0,
            }
        },
        computed:{
            period: function(){
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
            this.getTags();
            // 立ち上がるときの処理
            $('#experiencesModal').on('show.bs.modal', this.start);
        },
        methods:{
            getTags: function(){
                axios.get('/api/tags')
                    .then((res)=>{
                        tags = res.data;
                        this.tags = res.data;
                    });
            },
            tagApply: function(){
                document.getElementById("eTagsInput").addEventListener("change", (event) => {
                    const id = event.target.value;
                    $("#eTagsInput").val("");
                    // TODO タグを新規に追加する機能？
                    const index = this.tags.findIndex(t=> t.id == id);
                    if(index === -1) return;
                    if(this.selectedTags.findIndex(t=>t.id == id) === -1){  // 重複チェック
                        this.selectedTags.push(this.tags[index]);
                    }
                });
            },
            start: function(event){
                this.tagApply();
                // 入力情報をクリア
                this.refresh();

                this.viewOnly = true;

                // ボタンを取得
                const button = $(event.relatedTarget);

                // data-experience IDを取得
                const experienceId = button.data("experience");
                this.srcExperience = experiences.find(e => e.id === experienceId);
                if(typeof this.srcExperience === "undefined") return;
                
                // データ表示
                this.setProperty();
            },
            refresh: function(){
                this.selectedTags = [];
                this.nameText = "";
                if(document.getElementById("tagsInput") !== null) document.getElementById("tagsInput").value = "";
                this.noteText = "";
                this.selectedColor = "#A7CBD9";
                const date = new Date();
                this.startOn = this.dispDateCeilMinute(date) + "T" + this.dispTimeCeilMinute(date);
                this.endOn = this.dispDateCeilMinute(date) + "T" + this.dispTimeCeilMinute(new Date(date.setHours(date.getHours() + 1)));
                
                this.viewOnly = false;
                this.srcExperience = null;
            },
            setProperty: function(){
                this.nameText = this.srcExperience.name;
                this.selectedColor = "#"+this.srcExperience.color;
                const sd = new Date(this.srcExperience.start_on);
                this.startOn = this.dispDateCeilMinute(sd) + "T" + this.dispTimeCeilMinute(sd);
                const ed = new Date(sd.getTime() + this.srcExperience.quote);
                this.endOn = this.dispDateCeilMinute(ed) + "T" + this.dispTimeCeilMinute(ed);
                
                this.quote = (this.srcExperience.quote > 8.64e+7 ? Math.floor(this.srcExperience.quote / 8.64e+7) +"日 " : "") + ("0" + Math.floor((this.srcExperience.quote % 8.64e+7)/3.6e+6)).slice(-2) +":"+ ("0" + Math.floor((this.srcExperience.quote % 3.6e+6)/60000)).slice(-2);
                this.noteText = this.srcExperience.note;
                this.selectedTags = Array.from(this.srcExperience.tags);

                this.efficiency = this.srcExperience.efficiency;
            },
            deselectTag: function(tag){
                if(!this.selectedTags.includes(tag)) return;
                this.selectedTags = this.selectedTags.filter(n => n !== tag);
            },
            tentativeUpdate: function(){
                const startOnDate = new Date(this.startOn);
                const endOnDate = new Date(this.endOn);
                const timezone = startOnDate.getTimezoneOffset() * -1 >=0 ? "+"+(startOnDate.getTimezoneOffset()/60 * -1) : (startOnDate.getTimezoneOffset()/60 * -1);
                const period = new Date(this.endOn).getTime() - new Date(this.startOn).getTime();

                const experience = {
                    user_id: 1,
                    schedule_id: this.srcExperience.schedule_id,
                    name: this.nameText,
                    color: this.selectedColor.slice(-6),
                    start_on: this.dispDate(startOnDate) +" "+ this.dispTime(startOnDate) +" "+ timezone,
                    end_on: this.dispDate(endOnDate) +" "+ this.dispTime(endOnDate) +" "+ timezone,
                    quote: this.srcExperience.quote,
                    period: period,
                    efficiency: this.efficiency,
                    note: this.noteText,
                    tags: Array.from(this.selectedTags, t => t.id),
                }
                const index = experiences.indexOf(this.srcExperience);
                if(typeof index === -1) return;  // TODO return でいいのか

                $("#createExperiencesModal").modal("hide");
                axios.put('/api/experiences/' + this.srcExperience.id, experience)
                    .then((res) => {
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

            },
            deleteExperience: function(){
                const flag = confirm("本当に経験 " +this.srcExperience.name+ " を削除しますか？");
                if(!flag) return;
                axios.delete('/api/experiences/' + this.srcExperience.id)
                    .then((res) => {
                        $("#createExperiencesModal").modal("hide");
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
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