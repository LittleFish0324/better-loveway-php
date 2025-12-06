<?php
class PageCache {
    private static $cache_dir = '../cache/';
    private static $cache_time = 300; // 5分钟缓存
    
    public static function get($key) {
        $cache_file = self::$cache_dir . md5($key) . '.cache';
        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < self::$cache_time) {
            return file_get_contents($cache_file);
        }
        return false;
    }
    
    public static function set($key, $content) {
        if (!is_dir(self::$cache_dir)) {
            mkdir(self::$cache_dir, 0755, true);
        }
        $cache_file = self::$cache_dir . md5($key) . '.cache';
        return file_put_contents($cache_file, $content);
    }
    
    public static function clear() {
        $files = glob(self::$cache_dir . '*.cache');
        foreach ($files as $file) {
            unlink($file);
        }
    }
}
?>