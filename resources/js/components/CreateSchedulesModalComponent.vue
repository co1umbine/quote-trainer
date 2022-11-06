<template>
    <div class="modal" id="schedulesModal" tabindex="-1" aria-labelledby="RegisterSchedule" aria-hidden="true">
        <div class="modal-dialog">
            <div v-if="this.phase===0 || this.phase===1" class="modal-content">
                <div class="modal-header fb-center">
                    <h5 class="modal-title" id="RegisterSchedule">{{this.phase===1 ? "スケジュール詳細" : updateMode? "スケジュール編集" :"スケジュール新規作成"}}</h5>
                    <button v-show="this.phase===1" type="button" @click="() => {this.phase=3}" class="btn white-c bg-base-c mx-2 py-1">完了登録</button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <div aria-hidden="true">&times;</div>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="scheduleName" class="form-label">スケジュール名</label>
                        <input v-show="this.phase===0" type="text" class="form-control" id="scheduleName" v-model="nameText">
                        <div v-show="this.phase===1">{{nameText}}</div>
                    </div>

                    <div class="mb-3">
                        <div>
                            <label for="tags" class="form-label">タグ</label>
                            <div class="tag-area">
                                <button v-for="tag in selectedTags" :key="tag.id" type="button" @click="deselectTag(tag)" :style="'background-color: #'+ tag.color +';'+tagPointerStyle()" class="btn btn-sm my-2 ml-2 dark-c btn-rounded delete-self-btn">{{ tag.name }}</button>
                                <div v-if="selectedTags.length === 0" class="silent-text" >タグは設定されていません</div>
                            </div>
                            <select v-show="this.phase===0" name="tags" id="tagsInput" @change="tagApply" class="form-control">
                                <option hidden>選択してください</option>
                                <option v-for="tag in tags" :disabled="selectedTags.findIndex(t=>t.id === tag.id) !== -1" :key="tag.id" :value="tag.id">{{ tag.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="scheduleColor" class="form-label">色</label>
                        <input :disabled="this.phase===1" type="color" v-model="selectedColor" class="form-control form-control-color" id="scheduleColor" aria-describedby="colorHelp"/>
                        <button v-show="this.phase===0" type="button" @click="() => {selectedColor = '#a7cbd9'}" class="btn btn-sm bg-themeblue-c color-select-btn"></button>
                        <button v-show="this.phase===0" type="button" @click="() => {selectedColor = '#b5a7d9'}" class="btn btn-sm bg-themepurple-c color-select-btn"></button>
                        <button v-show="this.phase===0" type="button" @click="() => {selectedColor = '#d9a7cb'}" class="btn btn-sm bg-themepink-c color-select-btn"></button>
                        <button v-show="this.phase===0" type="button" @click="() => {selectedColor = '#d9b5a7'}" class="btn btn-sm bg-themeorange-c color-select-btn"></button>
                        <button v-show="this.phase===0" type="button" @click="() => {selectedColor = '#cbd9a7'}" class="btn btn-sm bg-themeyellow-c color-select-btn"></button>
                        <button v-show="this.phase===0" type="button" @click="() => {selectedColor = '#a7d9b5'}" class="btn btn-sm bg-themegreen-c color-select-btn"></button>
                    </div>

                    <div class="mb-3">
                        
                        <label>見積もり期間： {{ quote }}</label>
                        <div class="form-inline">
                        <input :disabled="this.phase===1" type="datetime-local" id="start-on"
                            name="start-on" v-model="startOn"
                            class="form-control">
                            
                        <!-- <label for="start-on"></label> -->
                        <span>&nbsp;～&nbsp;</span>

                        <input :disabled="this.phase===1" type="datetime-local" id="end-on"
                            name="end-on" v-model="endOn"
                            :min="startOn" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="scheduleNote" class="form-label">備考</label>
                        <textarea v-show="this.phase===0" type="text" class="form-control" id="scheduleNote" v-model="noteText"></textarea>
                        <div v-show="this.phase===1">{{ noteText }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    
                    <button v-show="this.updateMode" type="button" @click="deleteSchedule()" class="btn ol-high-c high-c mx-2">削除</button>
                    <button v-show="this.phase===0 && this.updateMode" type="button" @click="()=>{this.phase = 1; setProperty();}" class="btn ol-dark-c dark-c mx-2">キャンセル</button>
                    <button v-show="this.phase===1" type="button" @click="()=>{this.phase = 0;}" class="btn white-c bg-base-c mx-2">編集</button>
                    <button v-show="this.phase===0" type="button" @click="tentative" class="btn white-c bg-base-c mx-2">続行</button>
                </div>
            </div>


            <div v-if="this.phase===2" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="RegisterSchedule">{{this.phase===1 ? "スケジュール詳細" : updateMode? "スケジュール編集" :"スケジュール新規作成"}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <div aria-hidden="true">&times;</div>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quoteTune" class="form-label">{{nameText}} <span class="silent-text mb-2">{{quote}}</span></label>

                        <input v-if="simPeriodExp.length!==0 || simQuoteExp.length!==0" v-on:input="quoteTune" class="form-control px-0" type="range" :min="pivotQuote/2" :max="pivotQuote*2" step="600000" :value="quoteMilliSec" id="quoteTune">
                    </div>
                    <div class="mb-3">
                        <label for="simPeriod" class="form-label">近い時間で<b>完了した</b>経験です。</label>
                        <div v-for="ex in simPeriodExp" :key="ex.id" :id="ex.id">
                            <div :class="'rounded-corner-sm exp-compare-bar ' + (ex.id === -1 ? 'ol-high-c border-2':'')" :style="'background:#'+ex.color+'; width: '+ 50*((ex.id === -1 ? ex.quote : ex.period)/pivotQuote)+'%'">
                                <div>{{ ex.name }}</div> <div class="silent-text"> {{dispQuoteDate(new Date(ex.id === -1 ? ex.quote : ex.period)) }}</div>
                            </div>
                            <hr class="bar-base-line"/>
                        </div>
                        <div v-if="simPeriodExp.length===0" class="silent-text">見つかりませんでした。</div>
                    </div>
                    <div class="mb-3">
                        <label for="simQuote" class="form-label">近い時間に<b>見積もった</b>経験です。</label>
                        <div  v-for="ex in simQuoteExp" :key="ex.id" :id="ex.id">
                            <div :class="'rounded-corner-sm exp-compare-bar ' + (ex.id === -1 ? 'ol-high-c border-2':'')" :style="'background:#'+ex.color+'; width: '+ 50*(ex.quote/pivotQuote)+'%'">
                                <div>{{ ex.name }}</div>  <div class="silent-text">{{ dispQuoteDate(new Date(ex.quote)) }}</div>
                            </div>
                            <hr class="bar-base-line"/>
                        </div>
                        <div v-if="simQuoteExp.length===0" class="silent-text">見つかりませんでした。</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" @click="()=>{this.phase = 0;}" class="btn ol-dark-c dark-c mx-2">戻る</button>
                    <button v-show="this.phase===2" type="button" @click="()=>{this.updateMode ? updateSchedule() : registerSchedule()}" class="btn white-c bg-base-c mx-2">保存</button>
                </div>
            </div>


            <div v-if="this.phase===3" class="modal-content">
                
                <div class="modal-header fb-center">
                    <h5 class="modal-title" id="CompleteSchedule">スケジュール完了</h5>
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
                            <label for="eTags" class="form-label">タグ</label>
                            <div style="height: 45px;" id="eTags">
                                <button v-for="tag in selectedTags" :key="tag.id" type="button" @click="deselectTag(tag)" :style="'background-color: #'+ tag.color +';'+tagPointerStyle()" class="btn btn-sm my-2 ml-2 dark-c btn-rounded delete-self-btn">{{ tag.name }}</button>
                            </div>
                            <select name="tags" id="eTagsInput" @change="tagApply" class="form-control">
                                <option hidden>選択してください</option>
                                <option v-for="tag in tags" :disabled="selectedTags.findIndex(t=>t.id === tag.id) !== -1" :key="tag.id" :value="tag.id">{{ tag.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <input type="color" v-model="selectedColor" class="form-control form-control-color" id="experienceColor" aria-describedby="colorHelp"/>
                        <button v-show="this.phase===3" type="button" @click="() => {selectedColor = '#a7cbd9'}" class="btn btn-sm bg-themeblue-c color-select-btn"></button>
                        <button v-show="this.phase===3" type="button" @click="() => {selectedColor = '#b5a7d9'}" class="btn btn-sm bg-themepurple-c color-select-btn"></button>
                        <button v-show="this.phase===3" type="button" @click="() => {selectedColor = '#d9a7cb'}" class="btn btn-sm bg-themepink-c color-select-btn"></button>
                        <button v-show="this.phase===3" type="button" @click="() => {selectedColor = '#d9b5a7'}" class="btn btn-sm bg-themeorange-c color-select-btn"></button>
                        <button v-show="this.phase===3" type="button" @click="() => {selectedColor = '#cbd9a7'}" class="btn btn-sm bg-themeyellow-c color-select-btn"></button>
                        <button v-show="this.phase===3" type="button" @click="() => {selectedColor = '#a7d9b5'}" class="btn btn-sm bg-themegreen-c color-select-btn"></button>
                    </div>

                    <div class="mb-3">
                        <div><span class="small">見積もり期間： </span>{{ quote }} <span  class="small">に対して</span></div>
                        <label>かかった期間： {{ period }}</label>
                        <div class="form-inline">
                            <input disabled type="datetime-local"
                                name="start-on" v-model="startOn"
                                class="form-control">
                                
                                
                            <span>&nbsp;～&nbsp;</span>

                            <input type="datetime-local"
                                name="end-on" v-model="periodEndOn"
                                :min="startOn" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>体感効率 {{efficiency}}</label>
                        <div class="input-group form-inline flex-box fb-between">
                            悪い <input type="range" name="efficiency" min="-2" max="2" class="form-control px-0 mx-2" v-model="efficiency"> 良い
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="experienceNote" class="form-label">備考</label>
                        <textarea type="text" class="form-control" id="experienceNote" v-model="noteText"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="()=>{this.phase = 1; setProperty();}" class="btn ol-dark-c dark-c mx-2">キャンセル</button>
                    <button type="button" @click="complete" class="btn white-c bg-base-c mx-2">登録</button>
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

// const Phase = {
//     INPUT: 0,
//     VIEW: 1,
//     TUNE: 2,
//     DONE: 3,
// };

export default {
    data() {
        return {
            targetDate: targetDate,

            nameText: "",
            tags: [],
            selectedTags: [],
            selectedColor: "#A7CBD9",
            startOn: this.dispDateCeilMinute(targetDate.dateObject) + "T" + this.dispTimeCeilMinute(targetDate.dateObject),
            endOn: this.dispDateCeilMinute(targetDate.dateObject) + "T" + this.dispTimeCeilMinute(new Date(targetDate.dateObject.setHours(targetDate.dateObject.getHours() + 1))),
            noteText: "",

            phase: 0,
            updateMode: false,
            srcSchedule: null,

            simQuoteExp: [],
            simPeriodExp: [],
            pivotQuote: 0,

            periodEndOn: this.dispDateCeilMinute(targetDate.dateObject) + "T" + this.dispTimeCeilMinute(targetDate.dateObject),
            efficiency: 0,
        };
    },
    computed: {
        quote: function () {
            if (this.endOn !== "") {
                const endTime = new Date(this.endOn).getTime();
                const startTime = new Date(this.startOn).getTime();
                const quote = new Date(endTime - startTime);
                if (endTime <= startTime) {
                    return "";
                }
                return this.dispQuoteDate(quote);
            }
        },
        quoteMilliSec: function () {
            return new Date(this.endOn).getTime() - new Date(this.startOn).getTime();
        },
        period: function(){
            if(this.periodEndOn !== ""){
                const endTime = new Date(this.periodEndOn).getTime();
                const startTime = new Date(this.startOn).getTime();
                const period = new Date(endTime - startTime);

                if(endTime <= startTime){
                    return "";
                }
                return this.dispQuoteDate(period);
            }
        }
    },
    mounted() {
        this.getTags();
        // 立ち上がるときの処理
        $("#schedulesModal").on("show.bs.modal", this.start);
    },
    methods: {
        periodOrQuote: function(ex){
            if(typeof ex.period === "undefined"){
                return ex.quote;
            }else{
                return ex.period;
            }
        },
        getTags: function () {
            axios.get("/api/tags")
                .then((res) => {
                tags = res.data;
                this.tags = res.data;
            });
        },
        tagPointerStyle: function(){
            if(typeof this.phase === "undefined"){
                return  'pointer-events: none;';
            }else{
                return this.phase===1 ? 'pointer-events: none;':'';
            }
        },
        tagApply: function (event) {
            const id = event.target.value;
            $("#tagsInput").val("");
            $("#eTagsInput").val("");
            // TODO タグを新規に追加する機能？
            const index = this.tags.findIndex(t => t.id == id);
            if (index === -1)
                return; // 未知の文字列
            if (this.selectedTags.findIndex(t => t.id == id) === -1) { // 重複チェック
                this.selectedTags.push(this.tags[index]);
            }
        },
        deselectTag: function (tag) {
            if (!this.selectedTags.includes(tag))
                return;
            this.selectedTags = this.selectedTags.filter(n => n !== tag);
        },
        start: function (event) {
            // 入力情報をクリア
            this.refresh();
            // 新規作成なら空白のまま
            if (event.relatedTarget.id === "plusBtn")
                return;
            this.phase = 1;
            // this.viewOnly = true;
            this.updateMode = true;
            // ボタンを取得
            const button = $(event.relatedTarget);
            // data-schedule IDを取得
            const scheduleId = button.data("schedule");
            this.srcSchedule = schedules.find(s => s.id === scheduleId);
            // データ表示
            this.setProperty();
        },

        // 初期化
        refresh: function () {
            this.selectedTags = [];
            this.nameText = "";
            if (document.getElementById("tagsInput") !== null)
                document.getElementById("tagsInput").value = "";
            this.noteText = "";
            this.selectedColor = "#A7CBD9";
            const date = new Date();
            this.startOn = this.dispDateCeilMinute(date) + "T" + this.dispTimeCeilMinute(date);
            this.endOn = this.dispDateCeilMinute(date) + "T" + this.dispTimeCeilMinute(new Date(date.setHours(date.getHours() + 1)));
            this.phase = 0;
            this.updateMode = false;
            this.srcSchedule = null;
            this.simQuoteExp = [];
            this.simPeriodExp = [];
            this.pivotQuote = 0;
            this.periodEndOn = this.dispDateCeilMinute(targetDate.dateObject) + "T" + this.dispTimeCeilMinute(targetDate.dateObject)
        },

        // srcSchedule よりデータ欄を埋める
        setProperty: function () {
            if (typeof this.srcSchedule === "undefined" || this.srcSchedule === null)
                return;
            this.nameText = this.srcSchedule.name;
            this.selectedColor = "#" + this.srcSchedule.color;
            const sd = new Date(this.srcSchedule.start_on);
            this.startOn = this.dispDateCeilMinute(sd) + "T" + this.dispTimeCeilMinute(sd);
            const ed = new Date(sd.getTime() + this.srcSchedule.quote);
            this.endOn = this.dispDateCeilMinute(ed) + "T" + this.dispTimeCeilMinute(ed);
            this.noteText = this.srcSchedule.note;
            this.selectedTags = Array.from(this.srcSchedule.tags);
        },
        
        // quote tune画面 類似経験取得
        tentative: function () {
            if (this.nameText === "") {
                this.nameText = "（スケジュール名なし）";
            }
            this.pivotQuote = this.quoteMilliSec;
            this.simQuoteExp = [];
            this.simPeriodExp = [];
            
            const tentativeSchedule = this.createSchedule(-1);

            // 類似見積もり経験取得
            axios.get("/api/experiences/quoteInRange/" + (this.quoteMilliSec / 2).toString() + "/" + (this.quoteMilliSec * 2).toString())
                .then((res) => {
                    this.simQuoteExp = res.data;
                    if (this.simQuoteExp.length === 0)
                        return;
                
                    // 表示経験は6以下
                    if (this.simQuoteExp.length > 6) {  // 6つオーバー ソート->6つに絞る->予定を追加
                        this.simQuoteExp.sort((a, b) => {
                            if (a.quote > b.quote)
                                return -1;
                            if (a.quote < b.quote)
                                return 1;
                            return 0;
                        });

                        const newArray = [];
                        const radius = this.simQuoteExp.length / 2;
                        let dstScheIndex = 6;
                        for (let i = 0; i < 3; ++i) {
                            let left = 0 + (i * Math.floor(radius / 3));
                            let right = this.simQuoteExp.length - 1 - (i * Math.floor(radius / 3));
                            if (this.simQuoteExp[right].quote > this.quoteMilliSec) {
                                dstScheIndex = right+1;
                            }
                            else if (this.simQuoteExp[left].quote > this.quoteMilliSec) {
                                dstScheIndex = left+1;
                            }
                            newArray.splice(newArray.length / 2, 0, this.simQuoteExp[left], this.simQuoteExp[right]);
                        }
                        newArray.splice(dstScheIndex, 0, tentativeSchedule);
                        this.simQuoteExp = newArray;

                    }else{  // 6つ未満 予定追加->ソート
                        this.simQuoteExp.push(tentativeSchedule);
                        this.simQuoteExp.sort((a, b) => {
                            if (a.quote > b.quote)
                                return -1;
                            if (a.quote < b.quote)
                                return 1;
                            return 0;
                        });
                    }
                });

            // 類似完了経験取得
            axios.get("/api/experiences/periodInRange/" + (this.quoteMilliSec / 2).toString() + "/" + (this.quoteMilliSec * 2).toString())
                .then((res) => {
                    this.simPeriodExp = res.data;
                    if (this.simPeriodExp.length === 0)
                        return;
                    
                    // 表示経験は6以下
                    if (this.simPeriodExp.length > 6) {  // 6つオーバー ソート->6つに絞る->予定を追加
                        this.simPeriodExp.sort((a, b) => {
                            if (this.periodOrQuote(a) > this.periodOrQuote(b))
                                return -1;
                            if (this.periodOrQuote(a) < this.periodOrQuote(b))
                                return 1;
                            return 0;
                        });

                        const newArray = [];
                        const radius = this.simPeriodExp.length / 2;
                        let dstScheIndex = 6;
                        for (let i = 0; i < 3; ++i) {
                            let left = 0 + (i * Math.floor(radius / 3));
                            let right = this.simPeriodExp.length - 1 - (i * Math.floor(radius / 3));
                            if (this.simPeriodExp[right].period > this.quoteMilliSec) {
                                dstScheIndex = right+1;
                            }
                            else if (this.simPeriodExp[left].period > this.quoteMilliSec) {
                                dstScheIndex = left+1;
                            }
                            newArray.splice(newArray.length / 2, 0, this.simPeriodExp[left], this.simPeriodExp[right]);
                        }
                        newArray.splice(dstScheIndex, 0, tentativeSchedule);
                        this.simPeriodExp = newArray;

                    }else{  // 6つ以下 予定追加->ソート
                        this.simPeriodExp.push(tentativeSchedule);
                        this.simPeriodExp.sort((a, b) => {
                            if (this.periodOrQuote(a) > this.periodOrQuote(b))
                                return -1;
                            if (this.periodOrQuote(a) < this.periodOrQuote(b))
                                return 1;
                            return 0;
                        });
                    }
                });
            
            // quote tune フェーズへ
            this.phase = 2;
        },
        // quote tune の画面反映
        quoteTune: function(event){
            const slideMS = event.target.value;
            const s = new Date(this.startOn);
            const newEndDate = new Date(s.getTime() + Number(slideMS));
            this.endOn = this.dispDateCeilMinute(newEndDate) + "T" + this.dispTimeCeilMinute(newEndDate);
            this.simPeriodExp.find(e => e.id === -1).quote = Number(slideMS);
            this.simQuoteExp.find(e => e.id === -1).quote = Number(slideMS);
            this.simPeriodExp.sort((a, b) => {
                if (this.periodOrQuote(a) > this.periodOrQuote(b))
                    return -1;
                if (this.periodOrQuote(a) < this.periodOrQuote(b))
                    return 1;
                return 0;
            });
            this.simQuoteExp.sort((a, b) => {
                if (a.quote > b.quote)
                    return -1;
                if (a.quote < b.quote)
                    return 1;
                return 0;
            });
        },
        createSchedule: function (id) {
            const startOnDate = new Date(this.startOn);
            const timezone = startOnDate.getTimezoneOffset() * -1 >= 0 ? "+" + (startOnDate.getTimezoneOffset() / 60 * -1) : (startOnDate.getTimezoneOffset() / 60 * -1);
            return {
                id: id,
                user_id: 1,
                name: this.nameText,
                color: this.selectedColor.slice(-6),
                start_on: this.dispDate(startOnDate) + " " + this.dispTime(startOnDate) + " " + timezone,
                quote: this.quoteMilliSec,
                note: this.noteText,
                tags: Array.from(this.selectedTags, t => t.id),
            };
        },
        createExperience: function () {
            const startOnDate = new Date(this.startOn);
            const endOnDate = new Date(this.periodEndOn);
            const timezone = startOnDate.getTimezoneOffset() * -1 >=0 ? "+"+(startOnDate.getTimezoneOffset()/60 * -1) : (startOnDate.getTimezoneOffset()/60 * -1);
            const period = new Date(this.periodEndOn).getTime() - new Date(this.startOn).getTime();

            return {
                    user_id: 1,
                    schedule_id: this.srcSchedule.id,
                    name: this.nameText,
                    color: this.selectedColor.slice(-6),
                    start_on: this.dispDate(startOnDate) +" "+ this.dispTime(startOnDate) +" "+ timezone,
                    end_on: this.dispDate(endOnDate) +" "+ this.dispTime(endOnDate) +" "+ timezone,
                    quote: this.srcSchedule.quote,
                    period: period,
                    efficiency: this.efficiency,
                    note: this.noteText,
                    tags: Array.from(this.selectedTags, t => t.id),
                };
        },
        registerSchedule: function () {
            const schedule = this.createSchedule(-1);
            schedules.push(schedule);
            $("#schedulesModal").modal("hide");
            axios.post("/api/schedules", schedule)
                .then((res) => {
            })
                .catch(function (error) {
                console.log(error);
            });
        },
        updateSchedule: function () {
            const schedule = this.createSchedule(-1);
            const index = schedules.indexOf(this.srcSchedule);
            if (typeof index === -1)
                return; // TODO return でいいのか
            schedules[index] = schedule;
            $("#schedulesModal").modal("hide");
            axios.put("/api/schedules/" + this.srcSchedule.id, schedule)
                .then((res) => {
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        deleteSchedule: function () {
            const flag = confirm("本当に予定 " + nameText + " を削除しますか？");
            if (!flag)
                return;
            const index = schedules.indexOf(this.srcSchedule);
            if (typeof index === -1)
                return; // TODO return でいいのか
            schedules.splice(index);
            $("#schedulesModal").modal("hide");
            axios.delete("/api/schedules/" + this.srcSchedule.id)
                .then((res) => {
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        complete: function(){
            const flag = confirm("予定 " + this.srcSchedule.name + " を経験として保存します。");
            if (!flag)
                return;
            const index = schedules.indexOf(this.srcSchedule);
            if (typeof index === -1)
                return; // TODO return でいいのか
            schedules.splice(index);
            
            const experience = this.createExperience();
            experiences.push(experience);
            $("#schedulesModal").modal("hide");
            axios.post("/api/experiences", experience)
                .then((res) => {
                })
                .catch(function (error) {
                    console.log(error);
                });
            
            axios.delete("/api/schedules/" + this.srcSchedule.id)
                .then((res) => {
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        dispQuoteDate: function (date) {
            if (date.getTime() > 86400000) {
                return this.dispPeriodDate(date) + "日 " + this.dispPeriodTime(date);
            }
            else {
                return this.dispPeriodTime(date);
            }
        },
        dispDate: function (date) {
            const YYYY = date.getFullYear();
            const MM = ("00" + (date.getMonth() + 1)).slice(-2);
            const DD = ("00" + date.getDate()).slice(-2);
            return YYYY + "-" + MM + "-" + DD;
        },
        dispTime: function (date) {
            const hh = ("00" + date.getHours()).slice(-2);
            const mm = ("00" + date.getMinutes()).slice(-2);
            const ss = ("00" + date.getSeconds()).slice(-2);
            return hh + ":" + mm + ":" + ss;
        },
        dispDateCeilMinute: function (date) {
            const mDate = new Date(date);
            if (mDate.getMinutes() > 50) {
                mDate.setMinutes(0);
                mDate.setHours(mDate.getHours() + 1);
            }
            const YYYY = mDate.getFullYear();
            const MM = ("00" + (mDate.getMonth() + 1)).slice(-2);
            const DD = ("00" + mDate.getDate()).slice(-2);
            return YYYY + "-" + MM + "-" + DD;
        },
        dispTimeCeilMinute: function (date) {
            const mDate = new Date(date);
            if (mDate.getMinutes() > 50) {
                mDate.setMinutes(0);
                mDate.setHours(mDate.getHours() + 1);
            }
            const hh = ("00" + mDate.getHours()).slice(-2);
            const mm = ("00" + (Math.ceil(mDate.getMinutes() / 10) * 10)).slice(-2);
            return hh + ":" + mm;
        },
        dispPeriodDate: function (date) {
            const ms = date.getTime();
            const DD = Math.floor(ms / 86400000);
            return DD;
        },
        dispPeriodTime: function (date) {
            const hh = ("00" + date.getUTCHours()).slice(-2);
            const mm = ("00" + date.getUTCMinutes()).slice(-2);
            return hh + ":" + mm;
        }
    },
    watch:{
        // phase: {
        //     handler: function(newValue, oldValue){
        //         console.log("phase " + oldValue +"->"+newValue);
        //     },
        //     immediate: true,
        // },
        // updateMode: {
        //     handler: function(newValue, oldValue){
        //         console.log("updateMode " + oldValue +"->"+newValue);
        //     },
        //     immediate: true,
        // },
        // srcSchedule: {
        //     handler: function(newValue, oldValue){
        //         let o = oldValue===null || oldValue===undefined ?"null":oldValue.id;
        //         let n = newValue===null?"null":newValue.id;
                
        //         console.log("srcSchedule " + o +"->" + n);
        //     },
        //     immediate: true,
        // },
        // simQuoteExp:{
        //     handler: function(newValue, oldValue){
        //         console.dir(newValue);
        //     },
        //     deep: true,
        //     immediate: true,
        // },
        // simPeriodExp:{
        //     handler: function(newValue, oldValue){
        //         console.dir(newValue);
        //     },
        //     deep: true,
        //     immediate: true,
        // }
    }
}
</script>