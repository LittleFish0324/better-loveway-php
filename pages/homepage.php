<?php
if ($templateMode) {
    include('./includes/header.php');
}

if (empty($QueryArr['p'])) {
    $nowPage = 0;
} else {
    $nowPage = intval($QueryArr['p']) - 1;
}

// 构建搜索条件
$whereConditions = [];
$params = [];

// 关键词搜索
if (!empty($QueryArr['search'])) {
    $searchString = "%" . htmlspecialchars($QueryArr['search']) . "%";
    $whereConditions[] = "(confessor like ? or to_who like ? or introduction like ? or content like ? or comment like ?)";
    $params[] = $searchString;
    $params[] = $searchString;
    $params[] = $searchString;
    $params[] = $searchString;
    $params[] = $searchString;
}

// 时间范围搜索
if (!empty($QueryArr['start'])) {
    $whereConditions[] = "time >= ?";
    $params[] = htmlspecialchars($QueryArr['start']) . " 00:00:00";
}

if (!empty($QueryArr['end'])) {
    $whereConditions[] = "time <= ?";
    $params[] = htmlspecialchars($QueryArr['end']) . " 23:59:59";
}

// 媒体内容搜索
if (!empty($QueryArr['image'])) {
    $whereConditions[] = "(image != '' or images != '' or images is not null)";
}

if (!empty($QueryArr['video'])) {
    $whereConditions[] = "video != '' and video is not null";
}

// 构建完整的WHERE子句
$whereClause = "";
if (!empty($whereConditions)) {
    $whereClause = "WHERE " . implode(" and ", $whereConditions);
}
?>
<script>
    function like(id) {
        mdui.dialog({
            title: '请输入图片中的验证码',
            content: '<center><div class="mdui-row"> <div class="mdui-col-xs-9"> <div class="mdui-textfield"> <input class="mdui-textfield-input" id="vCode" type="text" placeholder="请输入您的答案" /></div> </div> <div class="mdui-col-xs-3"> <img style="position: relative;top:15px" id="vcode" src="/api/vcode.php" /> </div> </div></center>',
            modal: true,
            buttons: [{
                    text: '取消'
                },
                {
                    text: '确认',
                    onClick: function(inst) {
                        requestApi("favorite", {
                            id: id,
                            vCode: $("#vCode").val(),
                            timestamp: this.timestamp = Date.parse(new Date()) / 1000
                        }, false, true, true, "")
                    }
                }
            ]
        });
    }
</script>
<?php

$flag = true;
try {
    $pdo = pdoConnect();
    
    // 构建计数查询
    $countSql = "SELECT count(*) from loveway_data $whereClause";
    $countStmt = $pdo->prepare($countSql);
    
    // 绑定计数查询参数
    $paramIndex = 1;
    foreach ($params as $param) {
        $countStmt->bindValue($paramIndex, $param);
        $paramIndex++;
    }
    $countStmt->execute();
    $rows = $countStmt->fetch();
    $rowCount = $rows[0];
    $sql = "select * from loveway_data $whereClause ORDER BY time DESC limit ?,?";
    $stmt = $pdo->prepare($sql);
    
    // 绑定参数
    $paramIndex = 1;
    foreach ($params as $param) {
        $stmt->bindValue($paramIndex, $param);
        $paramIndex++;
    }
    $stmt->bindValue($paramIndex, $nowPage * $PAGEMAX, PDO::PARAM_INT);
    $stmt->bindValue($paramIndex + 1, $PAGEMAX, PDO::PARAM_INT);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $flag = false;
?>
            <br /><br />
            <div class="mdui-card mdui-hoverable" style="border-radius: 16px">
            <div class="mdui-card-header">
                <img class="mdui-card-header-avatar" src="https://q1.qlogo.cn/g?b=qq&s=640&nk=<?php echo !empty($row['contact']) ? $row['contact'] : '10000'; ?>" />
                <div class="mdui-card-header-title"><?php echo $row['confessor']; ?><?php if (!empty($row['class'])) echo " ({$row['class']}班)"; ?></div>
                <div class="mdui-card-header-subtitle"><?php echo $row['time']; ?></div>
            </div>
                <?php
                // 收集所有媒体内容
                $has_video = !empty($row['video']);
                $has_single_image = !empty($row['image']);
                $has_multiple_images = false;
                $image_count = 0;
                
                // 检查多图片
                if (!empty($row['images'])) {
                    $images = json_decode($row['images'], true);
                    if (!is_array($images)) {
                        $images = explode(',', $row['images']);
                        $images = array_filter($images, function($img) { return !empty(trim($img)); });
                    }
                    $has_multiple_images = is_array($images) && count($images) > 0;
                    $image_count = $has_multiple_images ? count($images) : 0;
                }
                
                // 显示媒体内容
                if ($has_video || $has_single_image || $has_multiple_images) {
                    echo '<div class="mdui-card-media">';
                    
                    // 如果有视频，优先显示视频
                    if ($has_video) {
                        echo '<video style="width: 100%; max-height: 300px; object-fit: cover;" poster="/static/uploads/video-poster.jpg" controls>';
                        echo '<source src="' . $row['video'] . '" type="video/mp4">';
                        echo '您的浏览器不支持视频播放。';
                        echo '</video>';
                        
                        // 如果同时有图片，显示图片提示
                        if ($has_single_image || $has_multiple_images) {
                            $total_images = $image_count + ($has_single_image ? 1 : 0);
                            echo '<div class="mdui-card-media-overlay">';
                            echo '<div class="mdui-card-media-title" style="background: rgba(0,0,0,0.6); padding: 8px; border-radius: 0 0 0 16px;">';
                            echo '🎥 视频 + 📸 ' . $total_images . '张图片';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo '<div class="mdui-card-media-overlay">';
                            echo '<div class="mdui-card-media-title" style="background: rgba(0,0,0,0.4); padding: 8px; border-radius: 0 0 0 16px;">';
                            echo '🎥 包含视频';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                    // 没有视频时显示图片
                    elseif ($has_single_image) {
                        echo '<img style="max-height: 300px; width: 100%; object-fit: cover;" onclick="if($(this).attr(\'origin-src\') == undefined) { window.open($(this).attr(\'src\')) } else { window.open($(this).attr(\'origin-src\')) }" onerror="randomImage()" src="' . $row['image'] . '" />';
                    }
                    elseif ($has_multiple_images) {
                        echo '<img src="' . $images[0] . '" style="max-height: 300px; width: 100%; object-fit: cover; cursor: pointer;" onclick="window.open(\'' . $images[0] . '\')" />';
                        echo '<div class="mdui-card-media-overlay">';
                        echo '<div class="mdui-card-media-title" style="background: rgba(0,0,0,0.4); padding: 8px; border-radius: 0 0 0 16px;">';
                        echo '📸 共' . $image_count . '张图片';
                        echo '</div>';
                        echo '</div>';
                    }
                    
                    echo '</div>';
                } else {
                    // 如果都没有多媒体内容，显示分割线
                    echo '<div class="mdui-card-media"><div class="mdui-divider"></div></div>';
                }
                ?>
                <div class="mdui-card-primary">
                    <div class="mdui-card-primary-title">To <?php echo $row['to_who']; ?><?php if (!empty($row['ta_class'])) echo " ({$row['ta_class']}班)"; ?></div>
                    <div class="mdui-card-primary-subtitle">
                        <?php echo $row['introduction']; ?>
                    </div>
                </div>
                <div class="mdui-card-content">
                    <?php echo $row['content']; ?>
                </div>
                <div class="mdui-card-actions">
                    <a class="copy mdui-btn mdui-btn-icon mdui-float-right" style="color:#4F4F4F" href="javascript:void(0);" data-clipboard-text="
                    <?php
                    echo get_http_type() . $_SERVER['SERVER_NAME'];
                    if ($REWRITE) {
                        echo "/card/" . $row['id'];
                    } else {
                        echo '/?page=card&id=' . $row['id'];
                    }
                    ?>"><i class="mdui-icon material-icons">share</i></a>
                    </a>
                    <div id="comment-<?php echo $row['id'] ?>" class="mdui-float-right mdui-card-primary-subtitle">
                        <?php echo count(json_decode($row['comment'])) ?>
                    </div>
                    <a target="_blank" style="color:#4F4F4F" href="
                    <?php
                    if ($REWRITE) {
                        echo "/card/" . $row['id'];
                    } else {
                        echo '/?page=card&id=' . $row['id'];
                    }
                    ?>" class="mdui-btn mdui-btn-icon mdui-float-right">
                        <i class="mdui-icon material-icons">comment</i>
                    </a>
                    <div id="like-<?php echo $row['id'] ?>" class="mdui-float-right mdui-card-primary-subtitle">
                        <?php echo $row['favorite'] ?>
                    </div>
                    <button style="color:#4F4F4F" class="mdui-btn mdui-btn-icon mdui-float-right" onclick="like('<?php echo $row['id'] ?>')">
                        <i class="mdui-icon material-icons">favorite</i>
                    </button>
                </div>
            </div>

<?php
        }
    } else {
        echo '抱歉！操作数据库时出现了一个致命错误！';
    }
} catch (Exception $e) {
    echo '抱歉！连接数据库失败！';
    //echo $e->getMessage();
}
?>
<br /><br />
<?php
if ($flag) {
?>
    <div class="mdui-card mdui-hoverable" style="border-radius: 16px">
        <div class="mdui-card-media">
            <img style="max-height: 2000px" onerror="randomImage()" src="/static/uploads/compressed.png" />
        </div>
        <div class="mdui-card-primary">
            <div class="mdui-card-primary-title">啥也没有……</div>
            <div class="mdui-card-primary-subtitle">这还没有任何表白呢！真的，什么都没有！</div>
        </div>
        <div class="mdui-card-content">
            点击左上角三条杠的"去表白"来打破这份宁静。<br><br>
        </div>
    </div>
<?php
} else {
    if (empty($QueryArr['search']) && empty($QueryArr['start']) && empty($QueryArr['end']) && empty($QueryArr['image']) && empty($QueryArr['video'])) {
        if (($rowCount / $PAGEMAX) - 1 > $nowPage) {
            echo '<a style="border-radius: 4px" href="?p=' . strval($nowPage + 2) . '" class="mdui-float-right mdui-btn mdui-btn-dense mdui-color-theme-accent mdui-ripple">下一页</a>';
        }
        echo ' <button onclick="jumpPage()" style="border-radius: 4px" class="mdui-float-right mdui-btn mdui-btn-dense">第' . strval($nowPage + 1) . '页</button> ';
        if ($nowPage > 0) {
            echo '<a style="border-radius: 4px" href="?p=' . strval($nowPage) . '" class="mdui-float-right mdui-btn mdui-btn-dense mdui-color-theme-accent mdui-ripple">上一页</a>';
        }
    }
}
?>