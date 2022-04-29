<?php

/** @var yii\web\View $this */
/** @var string $content */
$request = \Yii::$app->request;

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body class="d-flex flex-column h-100">
<!-- import Vue before Element -->
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<!-- 引入样式 -->
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
<!-- 引入组件库 -->
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
<style>
    .el-checkbox-group{
        font-size: 14px!important;
    }
</style>
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Supplier', 'url' => ['/supplier']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div id="app" class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left">&copy; My Company <?= date('Y') ?></p>
        <p class="float-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script>
    const id = '<?=$request->get('id') ? $request->get('id') : 'lte10'?>';
    const name = '<?=$request->get('name') ? $request->get('name') : ''?>';
    const code = '<?=$request->get('code') ? $request->get('code') : ''?>';
    const status = '<?=$request->get('status') ? $request->get('status') : ''?>';

</script>
<script>

    new Vue({
        el: '#app',
        data: function() {
            return {
                visible: false,
                formInline: {
                    id,
                    name,
                    code,
                    status
                },
                idSelected: [],
                domLists: [],
                selectAllText: 'Select All',
                allSelected: false,
                allPageSelected: false
            }
        },
        watch: {
            idSelected: {
                handler(val) {
                    this.selectAllText = val.length === this.domLists.length ? 'Clear All' : 'Select All'
                    this.allSelected = val.length === this.domLists.length
                },
                deep: true
            }
        },
        methods: {
            checkAllPage() {
                this.allPageSelected = true
            },
            selectChangeHandle(val) {
                console.info(val)
            },
            onSubmit() {
                window.location.href="?" + $.param(this.formInline)

            },
            onReset() {
                this.formInline = {}
                window.location.href="?"
            },
            handleCheckAll() {
                const checkedValues = []
                const domLists = document.querySelectorAll('.el-checkbox__original')
                this.domLists = document.querySelectorAll('.el-checkbox__original')
                if(this.idSelected.length === this.domLists.length){
                    this.idSelected = []
                    this.allSelected = false
                    this.allPageSelected = false
                    return false
                }

                console.info(domLists)
                domLists.forEach((item)=>{
                    checkedValues.push(item.value / 1)
                })

                this.idSelected = checkedValues
            },
            doExport() {
                const formData = new FormData();
                formData.append("selectedIds", this.idSelected)
                formData.append("allSelected", this.allPageSelected)
                formData.append("name", name)
                formData.append("code", code)
                formData.append("status", status)
                formData.append("_csrf", '<?=$request->getCsrfToken()?>')
                const request = new XMLHttpRequest()
                request.open("POST", "/supplier/export")
                request.send(formData)
                request.onreadystatechange = function () {
                    console.info(this)
                    if(this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                        csvString = "data:application/csv," + encodeURIComponent(this.response);
                        let btn = document.createElement('a');
                        btn.setAttribute("href", csvString);
                        btn.setAttribute("target", '_blank');
                        btn.setAttribute("download", "data.csv");
                        btn.click()
                    }





                    // Request finished. Do processing here.
                };
            }
        }
    })
</script>
