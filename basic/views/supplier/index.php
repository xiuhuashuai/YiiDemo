<?php

use app\models\Supplier;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $request */
/* @var $searchModel */

$this->title = 'Suppliers';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <el-button @click="onReset">Reset</el-button>
        <el-button type="success" :disabled="idSelected.length === 0" @click="doExport">Export</el-button>
    </p>
    <p>
        <el-alert v-if="allSelected && !allPageSelected" type="success">
            <template slot="title">
                All 5 conversations on this page have been selected. <span :style="{marginLeft: '8px'}">
                    <el-link @click="checkAllPage">Select all conversations that match this search</el-link>
                </span>

            </template>
        </el-alert>
        <el-alert v-if="allPageSelected && allSelected" type="info">
            <template slot="title">
                All conversations in this search have been selected. <span :style="{marginLeft: '8px'}">
                    <el-link @click="handleCheckAll">Clear selection</el-link>
                </span>

            </template>
        </el-alert>
    </p>

        <el-checkbox-group v-model="idSelected" @change="selectChangeHandle">
            <p>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute'=>'Select',
                        'format' => ['raw'],
                        'value'=>function($data) {
                            return "<el-checkbox :label='$data->id'></el-checkbox>";
                        },
                        'header'=>'<el-button size="mini" @click="handleCheckAll">{{selectAllText}}</el-button>',
                        // 你可以在这配置更多的属性
                    ],
                    [
                        'attribute'=>'id',
                        'headerOptions'=>['class'=>'text-center'],
                        'contentOptions'=>['class'=>'text-center'],
                        'filter'=>Html::activeDropDownList($searchModel, 'id',
                            [
                                ''=>'All',
                                'gt10'=>'>10',
                                'gte10'=>'>=10',
                                'lt10'=>'<10',
                                'lte10'=>'<=10'
                            ], ['class'=>'form-control']
                        )
                    ],
                    [
                        'attribute'=>'name',
                        'headerOptions'=>['class'=>'text-center'],
                        'contentOptions'=>['class'=>'text-center']
                    ],
                    [
                        'attribute'=>'code',
                        'headerOptions'=>['class'=>'text-center'],
                        'contentOptions'=>['class'=>'text-center']
                    ],
                    [
                        'attribute'=>'t_status',
                        'filter'=>Html::activeDropDownList($searchModel, 't_status', [''=>'All','ok'=>'OK','hold'=>'Hold'], ['class'=>'form-control'])
                    ],
                ],
            ]); ?>
            </p>
        </el-checkbox-group>







</div>

