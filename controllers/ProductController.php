<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use app\models\ProductForm;
use app\models\ProductCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;
use yii\web\UploadedFile;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'roles' => ['manageProduct'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['purchase'],
                        'roles' => ['purchaseProduct']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => []
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Display category name instead of category id
        $category_names = [];
        $rows = $dataProvider->getModels();
        foreach ($rows as &$row) {
            if(!isset($category_names[$row->category_id])) {
                $category_names[$row->category_id] = ProductCategory::findNameById($row->category_id);
            }
            $row->category_id = $category_names[$row->category_id];
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        // Display category name alongside category id if user can manage product
        if (\Yii::$app->user->can('manageProduct')) {
            $model->category_id = $model->category_id . ' (' .ProductCategory::findNameById($model->category_id) . ')'; 
        } else {
            $model->category_id = ProductCategory::findNameById($model->category_id); 
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductForm();

        if($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isPost) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            }
            $save_id = $model->upload();
            if ($save_id) {
                return $this->redirect(['view', 'id' => $save_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $db = $this->findModel($id);
        $model = new ProductForm();
        foreach(['name', 'description', 'category_id', 'price', 'quantity_available'] as $attribute) {
            $model[$attribute] = $db[$attribute];
        }

        if($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isPost) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            }
            $save_id = $model->upload($db);
            if ($save_id) {
                return $this->redirect(['view', 'id' => $save_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Purchase action
     */
    public function actionPurchase($id)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->runAction('login');
        }   
        
        if (Yii::$app->user->can('purchaseProduct')) {
            // 
            return Yii::$app->runAction('order/index');
        }
    }
    
    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
