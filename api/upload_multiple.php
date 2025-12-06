<?php
header('content-type:application/json');
session_start();
include('../config.php');
include('../includes/function.php');

// 验证码检查
if ($_SESSION['vcode'] != md5($_POST['vcode'] . $VERIFICATION_KEY) && $IMAGE_VERIFICATION) {
    exit('{"code":-2,"msg":"抱歉，人机验证失败"}');
}

// 时间戳检查
$timestamp = intval($_POST['timestamp']);
if ($timestamp - time() > 60 || time() - $timestamp > 60) {
    exit('{"code":-5,"msg":"提交失败！请检查您的系统时间！"}');
}

try {
    // 检查是否有文件上传
    if (empty($_FILES)) {
        exit('{"code":-1,"msg":"没有检测到上传的文件！"}');
    }
    
    $uploaded_images = [];
    $uploaded_video = null;
    
    // 处理图片上传
    if (!empty($_FILES['images'])) {
        $images = $_FILES['images'];
        $image_count = count($images['name']);
        
        if ($image_count > 9) {
            exit('{"code":-3,"msg":"图片最多只能上传9张！"}');
        }
        
        for ($i = 0; $i < $image_count; $i++) {
            if ($images['error'][$i] === UPLOAD_ERR_OK) {
                $file_info = [
                    'name' => $images['name'][$i],
                    'type' => $images['type'][$i],
                    'tmp_name' => $images['tmp_name'][$i],
                    'size' => $images['size'][$i],
                    'error' => $images['error'][$i]
                ];
                
                $result = upload_file($file_info, 'image');
                if ($result['code'] == 1) {
                    $uploaded_images[] = $result['path'];
                } else {
                    exit(json_encode($result));
                }
            }
        }
    }
    
    // 处理视频上传
    if (!empty($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $video_result = upload_file($_FILES['video'], 'video');
        if ($video_result['code'] == 1) {
            $uploaded_video = $video_result['path'];
        } else {
            exit(json_encode($video_result));
        }
    }
    
    // 返回成功结果
    exit(json_encode([
        'code' => 1,
        'msg' => '上传成功！',
        'images' => $uploaded_images,
        'video' => $uploaded_video
    ]));
    
} catch (Exception $e) {
    exit('{"code":-1,"msg":"上传失败！' . $e->getMessage() . '"}');
}

function upload_file($file, $type) {
    global $ALLOWED_IMAGE_TYPES, $ALLOWED_VIDEO_TYPES, $MAX_IMAGE_SIZE, $MAX_VIDEO_SIZE;
    
    // 检查上传是否成功
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error_messages = [
            UPLOAD_ERR_INI_SIZE => '上传的文件超过了 php.ini 中 upload_max_filesize 指令的限制',
            UPLOAD_ERR_FORM_SIZE => '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 指令指定的值',
            UPLOAD_ERR_PARTIAL => '文件只有部分被上传',
            UPLOAD_ERR_NO_FILE => '没有文件被上传',
            UPLOAD_ERR_NO_TMP_DIR => '找不到临时文件夹',
            UPLOAD_ERR_CANT_WRITE => '文件写入失败',
            UPLOAD_ERR_EXTENSION => '文件上传被PHP扩展停止'
        ];
        
        $error_msg = isset($error_messages[$file['error']]) ? $error_messages[$file['error']] : '未知上传错误（错误代码：' . $file['error'] . '）';
        return ['code' => -1, 'msg' => '上传失败：' . $error_msg];
    }
    
    // 检查文件类型
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if ($type == 'image') {
        if (!in_array($file_ext, $ALLOWED_IMAGE_TYPES)) {
            return ['code' => -3, 'msg' => '不支持的图片格式！只支持：' . implode(', ', $ALLOWED_IMAGE_TYPES)];
        }
        $max_size = $MAX_IMAGE_SIZE * 1024 * 1024; // MB to bytes
    } elseif ($type == 'video') {
        if (!in_array($file_ext, $ALLOWED_VIDEO_TYPES)) {
            return ['code' => -3, 'msg' => '不支持的视频格式！只支持：' . implode(', ', $ALLOWED_VIDEO_TYPES)];
        }
        $max_size = $MAX_VIDEO_SIZE * 1024 * 1024; // MB to bytes
    } else {
        return ['code' => -3, 'msg' => '不支持的文件类型！'];
    }
    
    // 检查文件大小
    if ($file['size'] > $max_size) {
        $max_size_mb = $type == 'image' ? $MAX_IMAGE_SIZE : $MAX_VIDEO_SIZE;
        return ['code' => -4, 'msg' => '文件过大！最大允许' . $max_size_mb . 'MB'];
    }
    
    // 检查上传目录
    $upload_dir = realpath(__DIR__ . '/../static/uploads');
    if (!is_dir($upload_dir)) {
        return ['code' => -1, 'msg' => '上传目录不存在！'];
    }
    
    if (!is_writable($upload_dir)) {
        return ['code' => -1, 'msg' => '上传目录不可写！请检查权限设置。'];
    }
    
    // 生成唯一文件名
    $file_name = uniqid() . '_' . time() . '.' . $file_ext;
    $upload_path = $upload_dir . '/' . $file_name;
    
    // 移动上传的文件
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        return [
            'code' => 1,
            'msg' => '上传成功！',
            'path' => '/static/uploads/' . $file_name
        ];
    } else {
        return ['code' => -1, 'msg' => '文件移动失败！'];
    }
}
?>