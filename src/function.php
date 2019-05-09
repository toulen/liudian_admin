<?php

/**
 * 判断是否存在自定义扩展的控制器
 * @param $extNamespace
 * @param $liudianNamespace
 * @param $controller
 * @return string
 */
function getController($extNamespace, $liudianNamespace, $controller){

    if(class_exists($extNamespace . '\\' . $controller)){

        return $extNamespace . '\\' . $controller;
    }

    return $liudianNamespace . '\\' . $controller;
}