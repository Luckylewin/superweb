<?php
/**
 * 控制过滤器, 集成了RBAC菜单权限验证
 */
namespace backend\components;

use Yii;
use yii\filters\AccessRule;
use yii\helpers\ArrayHelper;

//class AccessControl extends \yii\base\ActionFilter {
class AccessControl extends \yii\filters\AccessControl {


    public function beforeAction($action)
    {
        //-----菜单权限检查-----
        $user = $this->user;
        $actionId = $action->getUniqueId();

        $userId = $user->id;
        $permissions = Yii::$app->cache->getOrSet($userId . '-sys-permissions', function() {
            return Yii::$app->authManager->getPermissions();
        }, 1800);

        $allLimitedPermission = ArrayHelper::getColumn($permissions, 'ruleName');

        foreach ($this->rules as $i => $rule) {

            if (in_array($action->id, $rule->actions)) break;
            if(!Yii::$app->user->isGuest && Yii::$app->user->identity->username == 'admin') {
                try {
                    $this->rules[] = Yii::createObject(array_merge($this->ruleConfig, [
                        'actions' => [$action->id],
                        'allow' => true,
                    ]));
                }catch (\Exception $e) {

                }

            } else if (array_key_exists($actionId, $allLimitedPermission) && Yii::$app->user->can($actionId) == false) {
                try {
                    $this->rules[] = Yii::createObject(array_merge($this->ruleConfig, [
                        'actions' => [$action->id],
                        'allow' => false,
                    ]));
                } catch (\Exception $e) {

                }

            } else {
                try {
                    $this->rules[] = Yii::createObject(array_merge($this->ruleConfig, [
                        'actions' => [$action->id],
                        'allow' => true,
                    ]));
                } catch (\Exception $e) {

                }
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

}
