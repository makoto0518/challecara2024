# challecara2024
2024年12月にチャレキャラハッカソンで発表したアプリ「ConneCre」。
エンジニア同士のマッチングを行う。マッチングにはフロントエンド、バックエンド、フルスタックの属性、ユーザープロフィールを参照。
・開発案があるユーザーは募集→作成で募集を作成。欲しい人数や属性を設定。
・ユーザーは他ユーザーの開発案を閲覧し、応募をすることが出来る。
・募集ユーザーが応募を承認したらマッチング成功。チャットルームが開設される。
・ジャムマッチング機能を搭載。これは、ハッカソンなどでチーム決めをする際、自分の属性を設定しマッチングに参加することでシステムが自動で属性がばらけるようにチーム分けを行う。チーム結成後はチャットルームが使用可能。

動作方法
1.wampserverをインストール
2.wampディレクトリに公開しているソースコードを移植
3.mylib.phpの5～9行を自分の環境のものに変更する。その後、公開しているソースコードの中のconnecre.sqlを、mySQLのクエリ文実行欄で実行しテーブルを作成。
4.ソースコードが入っているディレクトリのlogin.phpをURLに入力し実行
5.ユーザーアカウントを作り、アプリ開始
