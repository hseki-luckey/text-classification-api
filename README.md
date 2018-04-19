## 概要
イベント名称がどの種別っぽいか判別するための簡単なアプリです。   
Text Classification APIを利用して作成しています。model_idなどは公開しておりません。   

詳しくは以下を参照してください。  
* 紹介記事 ： https://tech.linkbal.co.jp/2188/
* 動作確認 ： https://text-class.herokuapp.com/


## モデル作成方法  
### ①APIキーを取得  
以下よりAPIキーの発行を行う  
https://a3rt.recruit-tech.co.jp/product/textClassificationAPI/registered/

### ②CSVデータを作成
* ラベルとデータが対になっている
* １行目に「label,text」（行ヘッダ）を記入
* データ行数は100行〜1万行（行ヘッダ含む）
* 各レコードのテキスト文字数は1000文字以内かつ500単語以内

### ③dataset_idとdataset_urlを取得
```
$ curl -X POST https://api.a3rt.recruit-tech.co.jp/text_classification/v1/dataset -d apikey=[APIキー]
```

成功すると以下のようなjsonが返ってきます。
```
{
    "dataset_id": "[dataset_id]", 
    "dataset_url": "[dataset_url]", 
    "message": "ok", 
    "status": 0
}
```

### ④②で作成したCSVデータを③で取得したdataset_urlに配置
```
$ curl -X POST https://api.a3rt.recruit-tech.co.jp/text_classification/v1/model -d apikey=[APIキー] -d dataset_id=[dataset_id]
```

成功すると以下のような表示が返ってきます。
```
HTTP/1.1 100 Continue
HTTP/1.1 200 OK
x-amz-id-2: KXGDw+rQGxmUQRBLlFMEBGa9VYDaAyb7FWTHHtZ9XXiKLRREOyVdzotJbMdyDueV+kAWl4ixC+c=
x-amz-request-id: 1CB0B9106FF50161
Date: Mon, 29 May 2017 03:20:33 GMT
ETag: "fa56102826836944ad4d532ddfbdcf20"
Content-Length: 0
Server: AmazonS3
```

### ⑤④で配置したデータセットに対して学習を開始
```
$ curl -X POST https://api.a3rt.recruit-tech.co.jp/text_classification/v1/model -d apikey=[APIキー] -d dataset_id=[dataset_id]
```

成功すると以下のようなjsonが返ってきます。
```
{
    "message": "ok", 
    "model_id": "[model_id]", 
    "status": 0
}
```

### ⑥⑤で学習したモデルのステータスを確認
```
$ curl -X GET 'https://api.a3rt.recruit-tech.co.jp/text_classification/v1/check_status?apikey=[APIキー]&model_id=[model_id]'
```

正常に動いていれば以下のようなjsonが返ってきます。
```
{
    "message": "ok", 
    "model_status": "running", 
    "status": 0
}
```
