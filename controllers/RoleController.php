<?php

namespace app\controllers;

use app\components\Constant;
use app\models\BusinessRoleChangeLog;
use app\models\Geos;
use app\models\Privilege;
use app\models\RoleGroup;
use app\models\ServiceCountryCitys;
use app\models\Vendor;
use Yii;
use app\models\Role;
use app\models\RoleSearch;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\models\StationUser;
use yii\data\ArrayDataProvider;
use app\helpers\Helper;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends BaseController
{
    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Role model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);


        return $this->render('view', [
            'model' => $model,
            'privileges' => $model->getLayerPrivileges(),
        ]);
    }

    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Role([
            'status' => Constant::STATUS_ENABLE,
        ]);
        if ($model->load(Yii::$app->request->post()) && $model->saveWithPrivileges()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->status = Constant::STATUS_ENABLE;

            return $this->render('create', [
                'model' => $model,
                'privileges' => $model->getLayerPrivileges()
            ]);
        }
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->getRequest()->post()) && $model->saveWithPrivileges()) {
            $this->clearUserCache();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
            'privileges' => $model->getLayerPrivileges()
        ]);
    }

    /**
     * Deletes an existing Role model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Constant::STATUS_DISABLE;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
