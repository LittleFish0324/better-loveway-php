<?php
$IMAGE_VERIFICATION = true;

// 配置缓存
$config_cache = null;

function pdoConnect()
{
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('mysql:host=' . $GLOBALS['DB_HOST'] . ';dbname=' . $GLOBALS['DB_NAME'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASS']);
        // 设置PDO为持久连接
        $pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}

function get_http_type()
{
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return $http_type;
}

function titleChange()
{
    $title = '';
    if (!empty(getInfo($_GET['page']))) $title = getInfo($_GET['page']) . ' - ';
    $title = $title . getInfo('title');
    return '<script>$(document).attr("title","' . $title . '");</script>';
}

function listActive($pageName)
{
?>
    <script>
        pageArr = ['homePage', 'submitPage', 'morePage', 'aboutPage'];
        for (let i = 0; i < pageArr.length; i++) {
            if ($("#" + pageArr[i]).hasClass("mdui-list-item-active")) {
                $("#" + pageArr[i]).removeClass("mdui-list-item-active");
            }
        }
        $("#<?php echo $pageName; ?>Page").addClass("mdui-list-item-active");
    </script>
<?php
}

function getInfo($name)
{
    global $config_cache;
    
    // 如果配置缓存为空，一次性加载所有配置
    if ($config_cache === null) {
        try {
            $pdo = pdoConnect();
            $stmt = $pdo->prepare("SELECT name, value FROM loveway_config");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $config_cache = [];
            foreach ($rows as $row) {
                $config_cache[$row['name']] = $row['value'];
            }
        } catch (Exception $e) {
            include('./pages/err.php');
            return 'database connection failed';
        }
    }
    
    // 返回缓存中的配置值
    return isset($config_cache[$name]) ? $config_cache[$name] : '';
}

function hideSomethings()
{
?>
    <script>
        $('#appbar').css('display', 'none');
        $('#main-drawer').css('display', 'none');
        setTimeout(function() {
            inst.close();
        }, 50);
    </script>
<?php
}
?>