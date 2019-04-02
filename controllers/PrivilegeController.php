<?php


namespace app\controllers;

use app\helpers\Helper;
use Yii;
use app\models\Privilege;
use app\models\PrivilegeSearch;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

/**
 * PrivilegeController implements the CRUD actions for Privilege model.
 */
class PrivilegeController extends BaseController
{
    /**
     * Lists all Privilege models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrivilegeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $privileges = ArrayHelper::map(Privilege::find()->all(), 'id', function ($privilege) {
            return Yii::t('app', $privilege->name);
        });

        return $this->render('index', [
            'searchModel' => $searchModel,
            'privileges' => $privileges,
            'controllers' => $this->_controllers(),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Privilege model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Privilege model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @User: tanxianchen
     */
    public function actionCreate()
    {
        $model = new Privilege();
        if ($model->load(Yii::$app->request->post()) && $model->savePrivilege()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            // 获取1级2级菜单列表
            return $this->render('create', [
                'model' => $model,
                'pidList' => $model->getPidList(),
                'allControllersList' => $this->_controllers(),
            ]);
        }
    }


    /**
     * 获取传入controller下所有action
     * @return [json]       [description]
     * @User: tanxianchen
     * @Date: 2019-02-20
     */
    public function actionGetControllersAction()
    {
        $cname = Yii::$app->request->post('cname');
        //Get all actions in current controller from privileges
        $privileges = ArrayHelper::getColumn(Privilege::findAll(['controller' => $cname]), 'action');
        //将下划线类型的Controller名称转换成驼峰形式
        $controller = ucfirst(preg_replace_callback('/([-_]+([a-z]{1}))/i', function ($matches) {
            return strtoupper($matches[2]);
        }, $cname));
        //获取controller下的所有action
        $methods = get_class_methods("app\\controllers\\" . $controller . 'Controller');
        $actions = [];
        //已经在权限数据库的不再显示
        if (!empty($methods)) {
            foreach ($methods as $method) {
                if (strpos($method, 'action') === 0 && $method != 'actions') {
                    $action = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '-', lcfirst(substr($method, 6))));
                    !in_array($action, $privileges) && $actions[$action] = $action;
                }
            }
        }
        return json_encode($actions);
    }

    /**
     * 获取传入pid的depth
     * @param  integer $id [description]
     * @return int   $result
     * @User: tanxianchen
     */
    public function actionGetPrivilegeDepth($id = 0)
    {
        $id = Yii::$app->request->post('id', $id);
        if ($id === 0) {
            return 1;
        }
        $privilege = Privilege::findOne(['id' => $id]);
        return intval($privilege->depth) + 1;
    }

    /**
     * Updates an existing Privilege model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @User: tanxianchen
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->savePrivilege()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'pidList' => $model->getPidList(),
                'allControllersList' => $this->_controllers(),
                'recordData' => $model->toArray(),
            ]);
        }
    }

    /**
     * Name: actionUpdateSequence
     * Desc: 修改菜单顺序
     * User: tanxianchen
     * Date: 2019-02-20
     * @param $pid
     * @return string
     */
    public function actionUpdateSequence($pid = null) {
        $privileges = Privilege::find()->where(['pid' => $pid])->orderBy('sequence ASC')->all();
        if (Yii::$app->getRequest()->isPost) {
            $ids = Yii::$app->getRequest()->post('id');
            $sequences = Yii::$app->getRequest()->post('sequence');
            try {
                $transaction = Yii::$app->getDb()->beginTransaction();
                foreach ($sequences as $k => $sequence) {
                    $privilege = Privilege::findOne($ids[$k]);
                    $privilege->sequence = $sequence;
                    if (!$privilege->save()) {
                        throw new Exception($privilege->getFirstErrorMessage());
                    }
                }
                $transaction->commit();
                Helper::Alert(Yii::t('app', 'Update sequence success!'), 'success');
            } catch (Exception $e) {
                Helper::Alert($e->getMessage(), 'error');
                $transaction->rollBack();
            }

        }
        return $this->render('update-sequence', ['pid' => $pid, 'privileges' => $privileges]);
    }

    /**
     * Deletes an existing Privilege model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Privilege model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Privilege the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Privilege::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}