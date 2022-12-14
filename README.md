# QUOTE trainer

https://quote-trainer.herokuapp.com/calendar/month

QUOTE trainerはタスク所要時間の見積もり精度を高めることを目指すカレンダーWebアプリです。

タスクの予定を入れる際に過去の見積もりと実際の完了時間を参照することで、ユーザーの時間感覚もアップデートさせつつ、素早く妥当な見積もりをサポートします。
![image](https://user-images.githubusercontent.com/38975435/199670485-1f5435bd-16b7-4bed-8601-8178a9a0000b.png)


## 問題意識
タスクの時間見積もりという作業から感覚的要素を取り除くことは困難です。
しかし人間にはベストなシナリオの計画を立てやすいという **計画錯誤** と呼ばれるバイアスがあります。

タスクの工数を洗い出し工期を算出するなどの見積もり手法も確かにありますが、日常的なタスクや個人的なタスクで毎回このような見積もりをすることは現実的ではありません。

## Quote trainerの機能
### 予定入力時の見積もり時間調整
一度入力した見積もり時間と類似した見積もり時間または完了時間の経験を表示し、それを参考に見積もり時間を調整できます。

![スクリーンショット_20221031_211526](https://user-images.githubusercontent.com/38975435/199005953-b15c0ba0-c739-4a49-ac31-16556c09b886.png)

### 予定完了時のレビュー機能
予定が完了したら、完了時間と効率の自己評価を行えます。効率の自己評価とは調子の良し悪しや稼働率の記録です。

![スクリーンショット_20221031_211927](https://user-images.githubusercontent.com/38975435/199006367-a03640d8-022a-4af9-a50e-fbecafdf39b8.png)


### タグ付けによるプロファイル機能
5つまで自由にタグを付与できます。今後フィルタリングや分析が行える機能を追加予定です。

![スクリーンショット 2022-10-31 211607](https://user-images.githubusercontent.com/38975435/199006426-2696e120-43cd-44bb-b3b9-de234a6ed217.png)


## 設計
未実装含む
### ユースケース図
![スクリーンショット_20221031_213139](https://user-images.githubusercontent.com/38975435/199008465-93a94e22-c3b1-414b-b3dc-ffa0e45691d4.png)

### クラス図
![スクリーンショット_20221031_212950](https://user-images.githubusercontent.com/38975435/199008493-e88f265f-bd21-4968-86e9-97fd0069bf93.png)

### ER図
![スクリーンショット_20221031_213111](https://user-images.githubusercontent.com/38975435/199008535-eaf8f6e6-0553-4352-8f82-d4d1526641b1.png)
![スクリーンショット_20221031_213119](https://user-images.githubusercontent.com/38975435/199008540-76ad31a9-a26f-4bb9-8764-8848176ff8cf.png)


### 画面
![スクリーンショット_20221031_212620](https://user-images.githubusercontent.com/38975435/199007515-e7fa3bb6-363c-46aa-9219-7fca32d9b5e8.png)

