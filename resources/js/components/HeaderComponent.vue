<template>
    <div class="container-fluid" style="background-color:#56688C;">
        <nav class="navbar" style="padding: 0.5rem 0.5rem;">
            <div>
                <button class="btn navbar-toggler svg-key-c py-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    
                    <svg width="27" height="21" alt="Menu" viewBox="0 0 36 28"  xmlns="http://www.w3.org/2000/svg">
                    <rect width="36" height="6" />
                    <rect y="11" width="36" height="6" />
                    <rect y="22" width="36" height="6" />
                    </svg>
                </button>
                <span class="navbar-brand mb-0 white-c align-middle d-none d-md-inline">QUOTE trainer</span>
                <span class="navbar-text h2 bold mb-0 white-c align-middle mr-2">{{ year }}</span>
                <button type="button" id="m-w" onclick="location.href='#'" class="btn btn-lg key-c ol-key-c align-items-center" style="padding: 0.1rem 0.4rem; border: 2px solid; font-size: 1.5rem;">月</button>
            </div>

            <div>
                <button class="btn svg-key-c" type="button">
                    <svg width="20" height="33.75" viewBox="0 0 32 54" stroke="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.860687" y="26.9055" width="5.77286" height="38.0678" transform="rotate(-45 0.860687 26.9055)"/>
                    <rect x="27.7787" width="5.77286" height="38.0069" transform="rotate(45 27.7787 0)" />
                    </svg>
                </button>
                <span class="navbar-text h2 mb-0 white-c align-middle">8月</span>
                <button class="btn svg-key-c" type="button">
                    <svg width="20" height="33.75" viewBox="0 0 32 54" stroke="none" transform="rotate(180)" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.860687" y="26.9055" width="5.77286" height="38.0678" transform="rotate(-45 0.860687 26.9055)"/>
                    <rect x="27.7787" width="5.77286" height="38.0069" transform="rotate(45 27.7787 0)" />
                    </svg>
                </button>
            </div>

            <div class="d-none d-md-block">
                <button class="btn svg-key-c" type="button" data-toggle="modal" data-target="#createSchedulesModal">
                    <svg width="34.5" height="34.5" viewBox="0 0 46 46"  xmlns="http://www.w3.org/2000/svg">
                    <rect x="19.7143" width="6.57143" height="46" />
                    <rect x="46" y="19.7143" width="6.57143" height="46" transform="rotate(90 46 19.7143)" />
                    </svg>
                </button>
                <button class="btn svg-key-c-no-fill" type="button">
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
                        
                        <button type="button" class="btn bg-base-c">続行</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {    
        data: () => ({
            year: ''
        }),

        mounted(){
            const path = location.pathname;
            const m_w = document.getElementById("m-w");

            const monthPath = "/calendar/month";
            const weekPath = "/calendar/week";
            const onClick = "location.href="
            const spinerElement = document.createElement('span');

            if(path === monthPath){
                m_w.textContent = "月";
                m_w.setAttribute('onClick', onClick + "'" +weekPath+"'");
            }else if(path === weekPath){
                m_w.textContent = "週";
                m_w.setAttribute('onClick', onClick + "'" +monthPath+"'");
            }

            this.year = new Date().getFullYear();
        },
    }
</script>