<?php
header('content-type:application/json');
session_start();
include('../config.php');
include('../includes/function.php');
include('../includes/badword.php');
$confessor = htmlspecialchars($_POST['name']);
$contact = htmlspecialchars($_POST['contact']);
$class = htmlspecialchars($_POST['class']);
$ta = htmlspecialchars($_POST['taName']);
$ta_class = htmlspecialchars($_POST['taClass'] ?? '');
$image_url = htmlspecialchars($_POST['image'] ?? '');
$images = $_POST['images'] ?? '';
$video = $_POST['video'] ?? '';
$introduction = htmlspecialchars($_POST['introduceTA']);
$content = htmlspecialchars($_POST['toTA']);

// 敏感词检测
$allText = $confessor . ' ' . $ta . ' ' . $introduction . ' ' . $content;
$badWordResult = checkBadWords($allText);

if ($badWordResult['hasBadWord']) {
    exit('{"code":-6,"msg":"' . addslashes(formatBadWordMessage($badWordResult)) . '","badWords":' . json_encode($badWordResult['badWords']) . '}');
}


$timestamp = intval(htmlspecialchars($_POST['timestamp']));
if (empty($confessor) || empty($ta) || empty($introduction) || empty($content)) {
    exit('{"code":-3,"msg":"表单未填写完整或存在错误！"}');
}

if ($_SESSION['vcode'] != md5(($_POST['vCode'] ?? $_POST['vcode'] ?? '') . $VERIFICATION_KEY) && $IMAGE_VERIFICATION) {
    exit('{"code":-2,"msg":"抱歉，人机验证失败","result":""}');
}

if ($timestamp - time() > 60 || time() - $timestamp > 60) {
    exit('{"code":-5,"msg":"提交失败！请检查您的系统时间！"}');
}

// 处理images数组，与前端保持一致：使用逗号分隔
$images_str = '';
if (is_array($images)) {
    $images_str = implode(',', $images);
} elseif (is_string($images)) {
    $images_str = $images;
}

$all = 'Kagamine Yes!' . strval($contact) . $confessor . $ta . $ta_class . $image_url . $images_str . $video . $introduction . $content . $class . strval($timestamp);

// 调试信息
error_log("Signature string: " . $all);
error_log("Received key: " . $_POST['key']);
error_log("Calculated key: " . md5($all));

if (md5($all) != $_POST['key']) {
    exit('{"code":-5,"msg":"签名验证失败！请检查数据完整性。"}');
}

try {
    $pdo = pdoConnect();
    
    // 生成唯一的ID，避免重复
    do {
        $love_id = rand(100000000, 999999999);
        $check_stmt = $pdo->prepare("SELECT id FROM loveway_data WHERE id = ?");
        $check_stmt->bindValue(1, $love_id);
        $check_stmt->execute();
    } while ($check_stmt->fetch());
    
    $stmt = $pdo->prepare("insert into loveway_data(id,favorite,confessor,contact,class,time,to_who,ta_class,introduction,image,content,images,video,comment)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bindValue(1, $love_id);
    $stmt->bindValue(2, 0);
    $stmt->bindValue(3, $confessor);
    $stmt->bindValue(4, $contact);
    $stmt->bindValue(5, $class ?: ''); // 确保class不为null
    $stmt->bindValue(6, date("Y-m-d H:i:s", time()));
    $stmt->bindValue(7, $ta);
    $stmt->bindValue(8, $ta_class ?: ''); // 确保ta_class不为null
    $stmt->bindValue(9, $introduction);
    $stmt->bindValue(10, $image_url ?: ''); // 确保image不为null
    $stmt->bindValue(11, $content);
    $stmt->bindValue(12, $images_str);
    $stmt->bindValue(13, $video ?: ''); // 确保video不为null
    $stmt->bindValue(14, "[]");
    
    if ($stmt->execute()) {
        exit('{"code":1,"id":"' . strval($love_id) . '","msg":"表白信息提交成功！"}');
    } else {
        $error_info = $stmt->errorInfo();
        exit('{"code":-2,"msg":"数据插入失败！错误信息：' . addslashes($error_info[2]) . '"}');
    }
} catch (PDOException $e) {
    // 记录详细错误到日志
    error_log("Submit PDO error: " . $e->getMessage());
    error_log("POST data: " . json_encode($_POST));
    exit('{"code":-1,"msg":"数据库错误：' . addslashes($e->getMessage()) . '"}');
} catch (Exception $e) {
    // 捕获其他可能的错误
    error_log("Submit general error: " . $e->getMessage());
    error_log("POST data: " . json_encode($_POST));
    exit('{"code":-1,"msg":"系统错误：' . addslashes($e->getMessage()) . '"}');
}
