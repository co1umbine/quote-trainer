<template>
    <div class="modal" id="createSchedulesModal" tabindex="-1" aria-labelledby="RegisterSchedule" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="RegisterSchedule">スケジュール新規作成</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="scheduleName" class="form-label">スケジュール名</label>
                            <input type="text" class="form-control" id="scheduleName">
                        </div>

                        <div class="mb-3">
                            <div>
                                <label for="tags" class="form-label">タグ</label>
                                <input name="tags" type="text" list="tags_list" id="tagsInput" class="form-control" placeholder="Type to search...">
                                <datalist id="tags_list">
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
                            <input type="color" v-model="selectedColor" class="form-control form-control-color" id="scheduleColor" aria-describedby="colorHelp"/>
                            <button type="button" @click="() => {selectedColor = '#a7cbd9'}" class="btn btn-sm bg-themeblue-c color-select-btn"></button>
                            <button type="button" @click="() => {selectedColor = '#b5a7d9'}" class="btn btn-sm bg-themepurple-c color-select-btn"></button>
                            <button type="button" @click="() => {selectedColor = '#d9a7cb'}" class="btn btn-sm bg-themepink-c color-select-btn"></button>
                            <button type="button" @click="() => {selectedColor = '#d9b5a7'}" class="btn btn-sm bg-themeorange-c color-select-btn"></button>
                            <button type="button" @click="() => {selectedColor = '#cbd9a7'}" class="btn btn-sm bg-themeyellow-c color-select-btn"></button>
                            <button type="button" @click="() => {selectedColor = '#a7d9b5'}" class="btn btn-sm bg-themegreen-c color-select-btn"></button>
                        </div>

                        <div class="mb-3">
                            
                            <label>見積もり期間： {{ quote }}</label>
                            <div class="form-inline">
                            <input type="datetime-local" id="start-on"
                                name="start-on" v-model="startOn"
                                class="form-control">
                                
                            <!-- <label for="start-on"></label> -->
                            <span>&nbsp;～&nbsp;</span>

                            <input type="datetime-local" id="end-on"
                                name="end-on" v-model="endOn"
                                :min="startOn" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="scheduleNote" class="form-label">備考</label>
                            <textarea  type="text" class="form-control" id="scheduleNote"></textarea>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        
                        <button type="button" class="btn white-c bg-base-c">続行</button>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {  
        data(){
            return {
                selectedColor: "#A7CBD9",
                targetDate: targetDate,
                startOn: this.dispDate(targetDate.dateObject) + "T" + this.dispCeilMinute(targetDate.dateObject),
                endOn: this.dispDate(targetDate.dateObject) + "T" + this.dispCeilMinute(new Date(targetDate.dateObject.setHours(targetDate.dateObject.getHours() + 1))),
                tags: ["タグの例1", "タグの例2", "タグの例3"],
                selectedTags: [],
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
            document.getElementById("tagsInput").addEventListener("input", () => {
                const elem = document.getElementById("tagsInput");
                const val = elem.value;
                elem.value = "";
                // タグを新規に追加する機能？
                // tags.indexOf("rounded snout")
                if(!this.selectedTags.includes(val)){
                    this.selectedTags.push(val);
                }
            });
            $('#createSchedulesModal').on('show.bs.modal', this.refresh);
        },
        methods:{
            refresh: function(){
                this.selectedTags = [];
                document.getElementById("scheduleName").value = "";
                document.getElementById("tagsInput").value = "";
                document.getElementById("scheduleNote").value = "";
                this.selectedColor = "#A7CBD9";
                const date = new Date();
                this.startOn = this.dispDate(date) + "T" + this.dispCeilMinute(date);
                this.endOn = this.dispDate(date) + "T" + this.dispCeilMinute(new Date(date.setHours(date.getHours() + 1)));
            },
            deselectTag: function(tag){
                if(!this.selectedTags.includes(tag)) return;
                this.selectedTags = this.selectedTags.filter(n => n !== tag);
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

                return hh + ":" + mm;
            },
            dispCeilMinute: function(date){
                const hh = ("00" + date.getHours()).slice(-2);
                const mm = ("00" + (Math.ceil(date.getMinutes()/10)*10)).slice(-2);

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