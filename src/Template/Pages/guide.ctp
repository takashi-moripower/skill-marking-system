<div class="guide pt-1">
    <h2>(1) 背景・目的</h2>
    <p>
        当サイトは、早稲田文理専門学校が平成２８年〜平成３０年の３か年で受託した文科省委託事業「専修学校版デュアル教育システム」の事業活動の一環として構築しています。早稲田文理専門学校が主幹となり「ゲーム・ＣＧ分野産学連携コンソーシアム」を組織し、全国の専門学校、ゲーム・ＣＧ分野企業が協力し、産学連携教育の質保証・向上を目的としています。
    </p>
    <h2>(2) 役割・機能</h2>
    <p>
        従来、インターンシップは学校と企業が個別に行なっていました。しかしながら、「ゲーム・ＣＧ分野産学連携コンソーシアム」に参加する学校・団体は全国に散在しており、全国規模でインターンシップを実施するには情報共有が必要不可欠です。これを解決するため、当事業ではインターンシップ仲介のために必要な情報を収集し共有するため、WＥＢシステムを構築しています。
    </p>
    <h4>
        WＥＢシステムは以下の機能を持っています。
    </h4>
    <ol>
        <li>
            学生は作品を登録し、企業は作品を閲覧します。
        </li>
        <li>
            企業は学生作品を評価することで、スキル認定を行います。
        </li>
        <li>
            企業はインターンシップを募集し、全国の学生はこれに応募します。
        </li>
        <li>
            企業は適正スキルを持つ学生をインターンシップに勧誘します。
        </li>
    </ol>
    <h2>(3) WＥＢシステムの構成</h2>
    <p>
        WＥＢシステムは以下２つから構成されています。人材マッチングは、スキル認定の機能を含んでいます。
    </p>
    <ul>
        <li>
            スキル認定システム（上記(2)の①、②）
        </li>
        <li>
            人材マッチングシステム（上記(2)の①、②、③、④）
        </li>
    </ul>

    <img src="<?= $this->Url->build('/img/guide/system01.png', 1) ?>" class="img-fluid"/>


    <h2>(4)	WＥＢシステムの説明</h2>
    <h3>① ログイン権限</h3>
    <p>
        当システムは、利用する方の立場・役割に応じて権限が付与されています。当システムにログインした際は権限により操作できる機能が分かれています。
    </p>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>No.</th>
                <th>立場・役割</th>
                <th>権限</th>
                <th>操作できる機能（一例）</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="text-center">1</th>
                <td>教員</td>
                <td>組織管理者</td>
                <td>組織に関わる管理を行う。</td>
            </tr>
            <tr>
                <th class="text-center">2</th>
                <td>学生</td>
                <td>技術者</td>
                <td>作品を登録する。</td>
            </tr>
            <tr>
                <th class="text-center">3</th>
                <td>ゲーム・ＣＧ企業</td>
                <td>採点者</td>
                <td>作品を評価する。</td>
            </tr>
        </tbody>
    </table>
    <p>
        組織管理者がユーザ登録する際は、立場・役割に合わせて適切な権限を設定して下さい。
    </p>
    <h3>② スキル評価レベル</h3>
    <p>
        学生が登録した作品について自己評価・企業評価を行う際に、スキルに対して１〜５段階でレベルを付与します。
    </p>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th class="w-20 text-center">1</th>
                <th class="w-20 text-center">2</th>
                <th class="w-20 text-center">3</th>
                <th class="w-20 text-center">4</th>
                <th class="w-20 text-center">5</th>
            </tr>
        </thead>        
        <tbody>
            <tr>
                <td>非常に悪い</td>
                <td>悪い</td>
                <td>普通</td>
                <td>良い</td>
                <td>非常に良い</td>
            </tr>
            <tr>
                <td>くそ</td>
                <td>だめ</td>
                <td>まあまあ</td>
                <td>よし</td>
                <td>めっちゃいい</td>
            </tr>
        </tbody>
    </table>

    <h3>③ ジャンル</h3>
    <p>
        現在のところ、ジャンルはシステム管理者のみが管理しています。追加してほしいジャンルがあれば、システム管理者まで連絡して下さい。
    </p>
    <h3>④ スキル認定システム利用手順</h3>
    <p>
        当システムは、権限に応じて以下の流れで適宜使用して下さい。
    </p>
    <h4>教員</h4>
    <ol>
        <li>システム管理者からログイン情報を取得し、システムにログインします。</li>
        <li>最初は１教員、１組織（学校）しか存在しません。最初の教員が学校全体を管理する学校管理者の位置付けとなります。</li>
        <li>必要に応じて、下位組織（学部、学科）を登録します。下位組織を管理する教員（担任）を登録することもできます。これにより、組織を手分けして管理することができます。</li>
        <li>組織に所属する学生を登録します。パスワードは仮で登録し、学生にログイン後に変更してもらうと手間が省けます。学生登録の際の権限は、「技術者」とします。</li>
        <li>当システムには予めデフォルトで共通スキルが登録されています。これ以外の学校独自のスキル分野・スキルは、必要に応じて登録して下さい。</li>
    </ol>
    <h4>学生</h4>
    <ol>
        <li>教員からアカウント情報（ID、パスワード）を教えてもらい、システムにログインします。</li>
        <li>まずはプロファイルにて性別、生年月日、紹介を正しく記載しましょう。これらの情報は、企業が学生を検索する際の重要な情報となります。</li>
        <li>作品を登録します。作品のジャンル、説明、作品データを記載して下さい。より詳しい情報があればあるほど企業が評価しやすくなります。</li>
        <li>登録した作品に対して評価してほしいスキルを付加して下さい。続いて自己評価としてスキルレベル（１〜５）を設定します。</li>
        <li>企業が作品についてスキル評価を行うことで、作品や学生のスキル評価を確認することができます。</li>
    </ol>
    <h4>企業</h4>
    <ol>
        <li>システム管理者からログイン情報を取得し、システムにログインします。</li>
        <li>作品一覧から作品を選んでスキルを評価します。</li>
        <li>企業が作品のスキルを評価することで、学生にスキルが積み上がることになります。学生一覧にて、積み上がり要約された学生のスキルを確認することができます。</li>
        <li>企業は、自身が所属する組織（学校）の学生・作品の閲覧のみに制限されています。他組織を閲覧したい場合は、システム管理者に連絡して対象組織に所属する必要があります。学校の下位組織（学部、学科）は、学校の組織管理者に連絡し組織に所属させてもらう手順となります。</li>
    </ol>

    <h2>(5)	人材マッチングシステム利用手順</h2>
    <p>
        スキル認定システムの手順に加えて、教員・学生・企業は、インターンシップを成立させるため以下の手順を踏むことになります。
    </p>
    <ol>
        <li>企業は、インターンシップなどの人材募集案件（公開、説明、対象組織、性別、期間）を登録します。続いて募集する案件について必要とするスキルを登録します。</li>
        <li>企業は、人材募集にマッチする学生を検索します。マッチした学生に対して人材募集への「勧誘」を行うことができます。「勧誘」したが学生はマーク状態となりコンタクト一覧に表示されます。コンタクトとは、１人の企業と１人の学生の接触状態を示します。</li>
        <li>企業がコンタクトした学生は「応募・見送」を選択し、学校の教員が「承認・否認」を選択します。コンタクトの状態が、学生（応募）、教員（承認）、企業（勧誘）となれば、その募集は成立したことになります。</li>
        <li>学生は、公開された人材募集を確認することができます。非公開にすると学生からは人材募集の一覧から確認することはできません。人材募集を公開すると、勧誘する前に学生から「応募」されることもあります。その際、企業は「勧誘」か「見送」を選択して下さい。</li>
    </ol>

    <p>
        以　上
    </p>
</div>