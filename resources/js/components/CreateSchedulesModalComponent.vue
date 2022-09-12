<template>
    <div class="modal" id="createSchedulesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">スケジュール新規作成</h5>
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
                                <input name="tags" list="tags_list" id="tags" class="form-control" placeholder="Type to search...">
                                <datalist id="tags_list">
                                    リストから選択
                                    <select name="tags">
                                        <option>追加するタグを選択</option>
                                        <option>タグの例1</option>
                                        <option>タグの例2</option>
                                        <option>タグの例3</option>
                                    </select>
                                </datalist>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm m-2 dark-c bg-key-c btn-rounded">指定中のタグ</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <!--<label for="scheduleColor" class="form-label">スケジュール固有色</label> -->
                            <input type="color" class="form-control form-control-color" id="scheduleColor" aria-describedby="colorHelp" value="#A7CBD9">
                        </div>

                        <div class="mb-3 form-inline">
                            

                            <input type="datetime-local" id="start-on"
                                name="start-on" value="2018-06-12T19:30"
                                min="2018-06-07T00:00" max="2018-06-14T00:00" class="form-control">
                                
                            <label for="start-on"> ～ </label>

                            <input type="datetime-local" id="end-on"
                                name="end-on" value="2018-06-12T19:30"
                                min="2018-06-07T00:00" max="2018-06-14T00:00" class="form-control">
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