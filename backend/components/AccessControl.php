<?php
/**
 * 控制过滤器, 集成了RBAC菜单权限验证
 */
namespace backend\components;

use Yii;
use yii\filters\AccessRule;
use yii\helpers\ArrayHelper;

class AccessControl extends \yii\filters\AccessControl
{
    public $skipRoutes = [
        'index/index',
        'index/frame',
    ];

    public function beforeAction($action)
    {
        $user = $this->user;
        $actionId = $action->getUniqueId();

        $userId = $user->id;
        $permissions = Yii::$app->cache->getOrSet($userId . '-sys-permissions', function() {
            return Yii::$app->authManager->getPermissions();
        }, 1800);

        $allLimitedPermission = ArrayHelper::getColumn($permissions, 'ruleName');

        foreach ($this->rules as $i => $rule) {
            if (in_array($action->id, $rule->actions)) break;

            if ($user->isGuest) {
                $this->allocatePermission($action, false);
            } else if($user->identity->username == 'admin') {
                $this->allocatePermission($action, true);
            } else if(in_array($actionId, $this->skipRoutes)) {
                $this->allocatePermission($action, true);
            } else if (array_key_exists($actionId, $allLimitedPermission) && Yii::$app->user->can($actionId) == false) {
                $this->allocatePermission($action, false);
            }  else {
                $this->allocatePermission($action, true);
            }
        }

        //----------
        $request = Yii::$app->getRequest();
        /* @var $rule AccessRule */
        foreach ($this->rules as $rule) {
            if ($allow = $rule->allows($action, $user, $request)) {
                return true;
            } elseif ($allow === false) {
                if (isset($rule->denyCallback)) {
                    call_user_func($rule->denyCallback, $rule, $action);
                } elseif ($this->denyCallback !== null) {
                    call_user_func($this->denyCallback, $rule, $action);
                } else {
                    $this->denyAccess($user);
                }
                return false;
            }
        }
        if ($this->denyCallback !== null) {
            call_user_func($this->denyCallback, null, $action);
        } else {
            $this->denyAccess($user);
        }
        return false;
    }

    private function allocatePermission($action, $access = true)
    {
        try {
            $this->rules[] = Yii::createObject(array_merge($this->ruleConfig, [
                'actions' => [$action->id],
                'allow' => $access,
            ]));
        } catch (\Exception $e) {

        }
    }

}
