<?php

namespace app\controllers;

use app\helpers\Helper;
use app\models\Role;
use c006\alerts\Alerts;
use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends BaseController
{

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        $roleModel = new Role();
        $roles = $roleModel->getActiveRoles();
        $role_desc = $this->getRoleDesc($model);
        $role_desc_arr = $roleModel->getActiveRoleDesc();
        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$model->load(Yii::$app->request->post())|| !$model->saveUser()) {
                    throw new Exception(Yii::t('app', $model->getFirstErrorMessage()));
                }
                $transaction->commit();
                return $this->redirect([
                    'view',
                    'id' => $model->id
                ]);
            } catch (Exception $e) {
                Helper::Alert($e->getMessage(), 'danger');
                $transaction->rollBack();
            }
        }
        return $this->render('create', [
            'model' => $model,
            'roles' => $roles,
            'role_desc' => $role_desc,
            'role_desc_arr' => $role_desc_arr,
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $roleModel = new Role();
        $roles = $roleModel->getActiveRoles();
        $role_desc = $this->getRoleDesc($model);
        $role_desc_arr = $roleModel->getActiveRoleDesc();
        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$model->load(Yii::$app->request->post())|| !$model->saveUser()) {
                    throw new Exception(Yii::t('app', $model->getFirstErrorMessage()));
                }
                $transaction->commit();
                return $this->redirect([
                    'view',
                    'id' => $model->id
                ]);
            } catch (Exception $e) {
                Helper::Alert($e->getMessage(), 'danger');
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
            'role_desc' => $role_desc,
            'role_desc_arr' => $role_desc_arr,
        ]);
    }

    public function getRoleDesc($model)
    {
        return isset($model->role) ? Role::findOne(['id'=>$model->role->id])->description : '';
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)
             ->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
