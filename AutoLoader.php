<?php

namespace ffan\dop;
/**
 * Class DopAutoLoader 自动加载类
 */
class AutoLoader
{
    /**
     * @var array 命名空间对应的目录列表
     */
    private static $namespace_set = array();

    /**
     * 某个类是否存在
     * @param string $full_name 类名
     * @return bool
     */
    public static function dopExist($full_name)
    {
        if (!self::tryLoadClass($full_name)) {
            return false;
        }
        return class_exists($full_name);
    }

    /**
     * 尝试加载一个文件
     * @param string $full_name 类名
     * @return bool
     */
    private static function tryLoadClass($full_name)
    {
        $ns_pos = strrpos($full_name, "\\");
        $ns = substr($full_name, 0, $ns_pos);
        if (!isset(self::$namespace_set[$ns])) {
            return false;
        }
        $base_path = __DIR__ . '/' . self::$namespace_set[$ns] . DIRECTORY_SEPARATOR;
        $class_name = substr($full_name, $ns_pos + 1);
        $file_name = $base_path . $class_name . '.php';
        if (!is_file($file_name)) {
            return false;
        }
        /** @noinspection PhpIncludeInspection */
        require_once $file_name;
        return true;
    }

    /**
     * @param string $full_name
     */
    public static function autoload($full_name)
    {
        self::tryLoadClass($full_name);
    }

    /**
     * 加入命名空间 和 文件夹 映射
     * @param array $ns_map
     */
    public static function add(array $ns_map)
    {
        self::$namespace_set += $ns_map;
    }
}

//注册加载处理函数
spl_autoload_register(['ffan\\dop\\AutoLoader', 'autoload']);
