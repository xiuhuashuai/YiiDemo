<?php

namespace app\controllers;

use app\models\Supplier;
use app\models\SupplierSearch;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\conditions\InCondition;
use yii\db\conditions\LikeCondition;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\LinkPager;

/**
 * SupplierController implements the CRUD actions for Supplier model.
 */
class SupplierController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Supplier models.
     *
     * @return string
     */
    public function actionIndex()
    {

        $request = \Yii::$app->request;
//        $query = Supplier::find();
//        if($request->get('id')){
//            switch($request->get('id')){
//                case "gt10":
//                    $query->where('id > 10');
//                    break;
//                case "lt10":
//                    $query->where('id < 10');
//                    break;
//                case "gte10":
//                    $query->where('id >= 10');
//                    break;
//                case "lte10":
//                    $query->where('id <= 10');
//                    break;
//            }
//        }
//
//        if($request->get('name')){
//            $query->where(new LikeCondition('name', 'LIKE', $request->get('name')));
//        }
//
//        if($request->get('code')){
//            $query->where(new LikeCondition('code', 'LIKE', $request->get('code')));
//        }
//
//        if($request->get('status')){
//            $query->where(['t_status'=>$request->get('status')]);
//        }
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => 5
//            ]
//        ]);

        $searchModel = new SupplierSearch();
        $searchModel->id = $request->get('id');
        $searchModel->name = $request->get('name');
        $searchModel->code = $request->get('code');
        $searchModel->t_status = $request->get('t_status');
        $dataProvider = $searchModel->search($request->queryParams);





        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'request'=> $request,
            'searchModel'=> $searchModel
        ]);
    }

    /**
     * Displays a single Supplier model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Supplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Supplier();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Supplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Supplier model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionExport(){
        $request = \Yii::$app->request;
        $query = Supplier::find();
        $headList = [
            "id","name","code","status"
        ];

        if($request->post('allSelected')){
            $dataList = $query->asArray()->all();
            return self::toExcel($dataList, $headList, 'ExportData', 'php://output');
        }

        if($request->post('name')){
            $query->where(new LikeCondition('name', 'LIKE', $request->get('name')));
        }

        if($request->post('code')){
            $query->where(new LikeCondition('code', 'LIKE', $request->get('code')));
        }

        if($request->post('status')){
            $query->where(['t_status'=>$request->get('status')]);
        }

        if($request->post('selectedIds')){
            $dataList = $query->where(
                new InCondition(
                    'id',
                    'IN',
                    explode(',', $request->post('selectedIds'))
                )
            )->asArray()->all();

            return self::toExcel($dataList, $headList, 'ExportData', 'php://output');
        }
    }

    /**
     * Finds the Supplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Supplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Supplier::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected static function toExcel($dataList,$headList,$fileName,$exportUrl){
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$fileName.'.csv"');
        header('Cache-Control: max-age=0');

        $fp = fopen($exportUrl, 'a');

        foreach ($headList as $key => $value) {
            $headList[$key] = iconv('utf-8', 'gbk', $value);
        }

        fputcsv($fp, $headList);

        $num = 0;
        $limit = 100000;
        $count = count($dataList);
        for ($i = 0; $i < $count; $i++) {

            $num++;

            if ($limit == $num) {
                ob_flush();
                flush();
                $num = 0;
            }

            $row = $dataList[$i];
            foreach ($row as $key => $value) {
                $row[$key] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $row);
        }
        // return $fileName;
    }
}
