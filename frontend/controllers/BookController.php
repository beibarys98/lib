<?php

namespace frontend\controllers;

use common\models\Book;
use common\models\BookSearch;
use common\models\Storage;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
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
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Book model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Storage::find()->where(['model_id' => $id]),
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Book();
        $model2 = new Storage();
        $model3 = new Storage();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())
                && $model2->load($this->request->post())
                && $model3->load($this->request->post())) {

                $cover = $uploadedFile = UploadedFile::getInstance($model2, 'cover');
                $book = $uploadedFile2 = UploadedFile::getInstance($model3, 'book');

                $model->save(false);

                $filePath = Yii::getAlias('@frontend/web/uploads/covers/')
                    . Yii::$app->security->generateRandomString(8)
                    . '.' . $uploadedFile->extension;
                $filePath2 = Yii::getAlias('@frontend/web/uploads/books/')
                    . Yii::$app->security->generateRandomString(8)
                    . '.' . $uploadedFile2->extension;

                if($cover && $uploadedFile->saveAs($filePath)){
                    $model2->model_id = $model->id;
                    $model2->file_path = $filePath;
                    $model2->save(false);
                }

                if($book && $uploadedFile2->saveAs($filePath2)){
                    $model3->model_id = $model->id;
                    $model3->file_path = $filePath2;
                    $model3->save(false);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
            $model2->loadDefaultValues();
            $model3->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'model2' => $model2,
            'model3' => $model3
        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model2 = Storage::find()
            ->andWhere(['model_id' => $id])
            ->andWhere(['or', ['like', 'file_path', '%.jpg', false],
                ['like', 'file_path', '%.jpeg', false]])
            ->one();
        if($model2 === null){
            $model2 = new Storage();
        }

        $model3 = Storage::find()
            ->andWhere(['model_id' => $id])
            ->andWhere(['or', ['like', 'file_path', '%.pdf', false]])
            ->one();
        if($model3 === null){
            $model3 = new Storage();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Storage::find()->where(['model_id' => $id]),
        ]);

        if ($this->request->isPost
            && $model->load($this->request->post())
            && $model->save(false)) {

            $model2->load($this->request->post());
            $model3->load($this->request->post());

            $cover = UploadedFile::getInstance($model2, 'cover');
            $book = UploadedFile::getInstance($model3, 'book');

            if($cover){
                $filePath = Yii::getAlias('@frontend/web/uploads/covers/').$cover->name;
            }

            if($book){
                $filePath2 = Yii::getAlias('@frontend/web/uploads/books/').$book->name;
            }

            if($cover && $cover->saveAs($filePath)){
                $model2->model_id = $model->id;
                $model2->file_path = $filePath;
                $model2->save(false);
            }

            if($book && $book->saveAs($filePath2)){
                $model3->model_id = $model->id;
                $model3->file_path = $filePath2;
                $model3->save(false);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'model2' => $model2,
            'model3' => $model3,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $storage = Storage::find()->andWhere(['model_id' => $id])->all();
        foreach ($storage as $s){
            unlink($s->file_path);
            $s->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
