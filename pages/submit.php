<?php
if ($templateMode) {
    include('./includes/header.php');
}
?>
<br /><br />
<div class="mdui-card mdui-hoverable" style="border-radius: 16px">
    <div class="mdui-card-primary">
        <div class="mdui-card-primary-title">立即表白</div>
        <div class="mdui-card-primary-subtitle">快向你喜欢的TA表白吧！</div>
    </div>
    <div class="mdui-divider"></div>
    <div class="mdui-card-content">
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">你的QQ号（可选）</label>
            <textarea id="qq" class="mdui-textfield-input" placeholder="12345678"></textarea>
        </div>
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">你的名字</label>
            <textarea id="name" class="mdui-textfield-input" placeholder="例如：邓台湾"></textarea>
        </div>
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">你的班级</label>
            <select id="class" class="mdui-select" mdui-select>
                <option value="">请选择班级</option>
                <?php
                // 班级范围设置 - 你可以修改这里的班级范围
                for ($i = 215; $i <= 262; $i++) {
                    echo "<option value=\"$i\">{$i}班</option>";
                }
                ?>
            </select>
        </div>
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">TA的名字</label>
            <textarea id="taName" class="mdui-textfield-input" placeholder="例如：陈回归"></textarea>
        </div>
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">TA的班级（可选）</label>
            <select id="taClass" class="mdui-select" mdui-select>
                <option value="">请选择班级</option>
                <?php
                // 班级范围设置 - 你可以修改这里的班级范围
                for ($i = 215; $i <= 262; $i++) {
                    echo "<option value=\"$i\">{$i}班</option>";
                }
                ?>
            </select>
        </div>
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">表白配图（可选）</label>
            
            <?php if ($UPLOAD_IMAGE) { ?>
            <!-- 多媒体上传区域 -->
            <div class="upload-section">
                <!-- 在线图片链接 -->
                <div class="mdui-textfield mdui-textfield-floating-label" style="margin-bottom: 16px;">
                    <label class="mdui-textfield-label">🔗 在线图片链接URL（不懂的不用管）</label>
                    <textarea id="image" class="mdui-textfield-input" placeholder=""></textarea>
                </div>
                
                <!-- 分割线 -->
                <div class="mdui-row">
                    <div class="mdui-col-xs-5"><div class="mdui-divider"></div></div>
                    <div class="mdui-col-xs-2" style="text-align: center; color: #999; font-size: 12px; padding: 8px 0;">或</div>
                    <div class="mdui-col-xs-5"><div class="mdui-divider"></div></div>
                </div>
                
                <!-- 本地上传区域 -->
                <div style="margin-top: 16px;">
                    <!-- 图片上传 -->
                    <div class="upload-card mdui-card mdui-ripple" style="margin-bottom: 16px; border-radius: 12px; overflow: hidden;">
                        <div class="mdui-card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 16px;">
                            <div class="mdui-card-header-avatar" style="background: rgba(255,255,255,0.2); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                📸
                            </div>
                            <div class="mdui-card-header-title">图片上传</div>
                            <div class="mdui-card-header-subtitle" style="color: rgba(255,255,255,0.8);">最多9张，单张不超过<?php echo $MAX_IMAGE_SIZE; ?>MB</div>
                        </div>
                        <div class="mdui-card-content" style="padding: 16px;">
                            <div class="mdui-row">
                        <div class="mdui-col-sm-6 mdui-col-xs-7">
                            <div class="upload-input-wrapper">
                                <input type="file" name="images[]" id="upload-images" accept="image/*" multiple style="display: none;">
                                <button type="button" class="mdui-btn mdui-btn-raised mdui-color-theme-accent mdui-ripple" onclick="document.getElementById('upload-images').click()" style="width: 100%;">
                                    选择图片
                                </button>
                            </div>
                        </div>
                        <div class="mdui-col-sm-2 mdui-col-xs-5" style="display: flex; align-items: center; justify-content: center; padding-top: 8px;">
                            <button type="button" class="mdui-btn mdui-btn-raised mdui-color-grey-300 mdui-ripple" onclick="resetImageSelection()" title="清除已选择的图片" style="min-width: 40px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                <i class="mdui-icon material-icons" style="font-size: 18px;">clear</i>
                            </button>
                        </div>
                        <div class="mdui-col-sm-4 mdui-col-xs-12" style="display: flex; align-items: center; justify-content: center; padding-top: 8px;">
                            <span class="upload-info" id="image-count-info">未选择图片</span>
                        </div>
                            </div>
                            
            <!-- 图片预览区 -->
            <div id="image-preview" class="preview-grid" style="margin-top: 16px; display: none;"></div>
                        </div>
                    </div>
                    
                    <!-- 视频上传 -->
                    <div class="upload-card mdui-card mdui-ripple" style="border-radius: 12px; overflow: hidden;">
                        <div class="mdui-card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 16px;">
                            <div class="mdui-card-header-avatar" style="background: rgba(255,255,255,0.2); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                🎥
                            </div>
                            <div class="mdui-card-header-title">视频上传</div>
                            <div class="mdui-card-header-subtitle" style="color: rgba(255,255,255,0.8);">1个视频，不超过<?php echo $MAX_VIDEO_SIZE; ?>MB</div>
                        </div>
                        <div class="mdui-card-content" style="padding: 16px;">
                            <div class="mdui-row">
                        <div class="mdui-col-sm-6 mdui-col-xs-7">
                            <div class="upload-input-wrapper">
                                <input type="file" name="video" id="upload-video" accept="video/*" style="display: none;">
                                <button type="button" class="mdui-btn mdui-btn-raised mdui-color-pink mdui-ripple" onclick="document.getElementById('upload-video').click()" style="width: 100%;">
                                    选择视频
                                </button>
                            </div>
                        </div>
                        <div class="mdui-col-sm-2 mdui-col-xs-5" style="display: flex; align-items: center; justify-content: center; padding-top: 8px;">
                            <button type="button" class="mdui-btn mdui-btn-raised mdui-color-grey-300 mdui-ripple" onclick="resetVideoSelection()" title="清除已选择的视频" style="min-width: 40px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                <i class="mdui-icon material-icons" style="font-size: 18px;">clear</i>
                            </button>
                        </div>
                        <div class="mdui-col-sm-4 mdui-col-xs-12" style="display: flex; align-items: center; justify-content: center; padding-top: 8px;">
                            <span class="upload-info" id="video-info">未选择视频</span>
                        </div>
                            </div>
                            
            <!-- 视频预览区 -->
            <div id="video-preview" style="margin-top: 16px; display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <!-- 仅在线图片链接 -->
            <div class="mdui-textfield mdui-textfield-floating-label">
                <textarea id="image" class="mdui-textfield-input" placeholder="https://fish1234.cn/static/uploads/compressed.png"></textarea>
            </div>
            <?php } ?>
        </div>
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">一句话介绍一下TA</label>
            <textarea id="introduceTA" class="mdui-textfield-input" placeholder="TA是一个优秀的人！"></textarea>
        </div>
        <div class="mdui-textfield">
            <label class="mdui-textfield-label">你要对TA说的话</label>
            <textarea id="toTA" class="mdui-textfield-input" rows="4" placeholder="你好，同志！"></textarea>
        </div>
    </div>

    <div class="mdui-card-actions">
        <button id="submitbtn" style="border-radius: 8px" class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right" onclick="submit()">
            发射！
        </button>
    </div>
    <style>
        .upload-section {
            margin-top: 16px;
        }
        
        .upload-card {
            transition: all 0.3s ease;
        }
        
        .upload-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        
        .upload-info {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }
        
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 8px;
        }
        
        .preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            background: #f5f5f5;
        }
        
        .preview-item img {
            width: 100%;
            height: 100px;
            object-fit: cover;
        }
        
        .preview-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
            padding: 6px 8px;
            font-size: 10px;
            line-height: 1.2;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }
        
        .preview-info small {
            color: rgba(255,255,255,0.8);
            font-size: 9px;
        }
        
        .upload-status {
            position: absolute;
            top: 4px;
            right: 4px;
            background: rgba(76, 175, 80, 0.9);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        @media (max-width: 600px) {
            .preview-grid {
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            }
        }
    </style>
    
    <script>
        function submit() {
            url = $("#url").val();
            if (<?php if ($IMAGE_VERIFICATION) echo 'true';
                else echo 'false'; ?>) {
                imageVerification(function(answer) {
                    request(answer)
                })
            } else {
                request('0000');
            }
        }

        // 存储已上传的文件
        var uploadedImages = [];
        var uploadedVideo = null;

        // 图片选择和预览
        $("#upload-images").on("change", function() {
            var files = $(this).prop('files');
            if (files.length > 9) {
                mdui.snackbar({
                    message: "最多只能选择9张图片！",
                    position: 'top'
                });
                resetImageSelection();
                return;
            }
            
            // 验证每个图片文件大小
            var maxImageSize = <?php echo $MAX_IMAGE_SIZE; ?> * 1024 * 1024; // MB to bytes
            for (var i = 0; i < files.length; i++) {
                if (files[i].size > maxImageSize) {
                    mdui.snackbar({
                        message: "图片 '" + files[i].name + "' 过大！最大允许 " + <?php echo $MAX_IMAGE_SIZE; ?> + "MB",
                        position: 'top',
                        timeout: 5000
                    });
                    // 完全重置文件选择状态
                    resetImageSelection();
                    return;
                }
                
                // 验证文件类型
                var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (allowedTypes.indexOf(files[i].type) === -1) {
                    mdui.snackbar({
                        message: "文件 '" + files[i].name + "' 格式不支持！只支持：JPG、PNG、GIF",
                        position: 'top',
                        timeout: 5000
                    });
                    // 完全重置文件选择状态
                    resetImageSelection();
                    return;
                }
            }
            
            // 更新选择信息
            $('#image-count-info').text('已选择 ' + files.length + ' 张图片');
            
            previewImages(files);
            uploadFiles('images');
        });

        // 视频选择和预览
        $("#upload-video").on("change", function() {
            var file = $(this).prop('files')[0];
            if (file) {
                var sizeMB = (file.size / 1024 / 1024).toFixed(2);
                
                // 验证视频文件大小
                var maxVideoSize = <?php echo $MAX_VIDEO_SIZE; ?> * 1024 * 1024; // MB to bytes
                if (file.size > maxVideoSize) {
                    mdui.snackbar({
                        message: "视频 '" + file.name + "' 过大！最大允许 " + <?php echo $MAX_VIDEO_SIZE; ?> + "MB",
                        position: 'top',
                        timeout: 5000
                    });
                    // 完全重置文件选择状态
                    resetVideoSelection();
                    return;
                }
                
                // 验证文件类型
                var allowedTypes = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/flv', 'video/webm'];
                if (allowedTypes.indexOf(file.type) === -1) {
                    mdui.snackbar({
                        message: "文件 '" + file.name + "' 格式不支持！只支持：MP4、AVI、MOV、WMV、FLV、WEBM",
                        position: 'top',
                        timeout: 5000
                    });
                    // 完全重置文件选择状态
                    resetVideoSelection();
                    return;
                }
                
                $('#video-info').text('已选择 (' + sizeMB + ' MB)');
                previewVideo(file);
                uploadFiles('video');
            }
        });

        function previewImages(files) {
            var preview = $('#image-preview');
            preview.empty();
            uploadedImages = [];
            
            // 只有在有文件时才显示预览区域
            if (files.length === 0) {
                preview.hide();
                return;
            }
            
            preview.show(); // 显示预览区域
            
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                if (!file.type.match('image.*')) continue;
                
                var reader = new FileReader();
                reader.onload = (function(file, index) {
                    return function(e) {
                        var sizeMB = (file.size / 1024 / 1024).toFixed(1);
                        var div = $('<div class="preview-item">' +
                            '<img src="' + e.target.result + '" alt="' + file.name + '">' +
                            '<div class="preview-info">' + file.name + '<br><small>' + sizeMB + ' MB</small></div>' +
                            '<div class="upload-status" style="display: none;">✓</div>' +
                            '</div>');
                        preview.append(div);
                        
                        // 添加动画效果
                        setTimeout(function() {
                            div.css('opacity', '0').animate({opacity: 1}, 300);
                        }, index * 50);
                    };
                })(file, i);
                reader.readAsDataURL(file);
            }
        }

        function previewVideo(file) {
            var preview = $('#video-preview');
            preview.empty();
            
            // 显示预览区域
            preview.show();
            
            var sizeMB = (file.size / 1024 / 1024).toFixed(2);
            var div = $('<div class="mdui-card" style="background: #f8f9fa;">' +
                '<div class="mdui-card-content" style="padding: 16px;">' +
                '<div style="display: flex; align-items: center; margin-bottom: 8px;">' +
                '<i class="mdui-icon material-icons" style="color: #f5576c; margin-right: 8px;">videocam</i>' +
                '<div style="flex: 1;">' +
                '<div style="font-size: 14px; font-weight: 500;">' + file.name + '</div>' +
                '<div style="font-size: 12px; color: #666;">大小: ' + sizeMB + ' MB</div>' +
                '</div>' +
                '</div>' +
                '<div class="mdui-progress">' +
                '<div class="mdui-progress-indeterminate"></div>' +
                '</div>' +
                '</div>' +
                '</div>');
            preview.append(div);
        }

        function uploadFiles(type) {
            imageVerification(function(answer) {
                var formData = new FormData();
                var timestamp = Date.parse(new Date()) / 1000;
                
                if (type === 'images') {
                    var files = $('#upload-images')[0].files;
                    if (!files || files.length === 0) {
                        mdui.snackbar({
                            message: "请先选择要上传的图片",
                            position: 'top'
                        });
                        return;
                    }
                    for (var i = 0; i < files.length; i++) {
                        formData.append('images[]', files[i]);
                    }
                } else if (type === 'video') {
                    var videoFile = $('#upload-video')[0].files[0];
                    if (!videoFile) {
                        mdui.snackbar({
                            message: "请先选择要上传的视频",
                            position: 'top'
                        });
                        return;
                    }
                    formData.append('video', videoFile);
                }
                
                formData.append('vcode', answer);
                formData.append('timestamp', timestamp);
                
                // 禁用对应的按钮，而不是所有按钮
                if (type === 'images') {
                    $('#upload-images').attr("disabled", "disabled");
                } else {
                    $('#upload-video').attr("disabled", "disabled");
                }
                $("#isLoading").show(100);
                
                $.ajax({
                    type: 'POST',
                    url: '/api/upload_multiple.php',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    timeout: 60000, // 60秒超时
                    success: function(rdata) {
                        $("#isLoading").hide(100);
                        // 重新启用对应的按钮
                        if (type === 'images') {
                            $('#upload-images').removeAttr("disabled");
                        } else {
                            $('#upload-video').removeAttr("disabled");
                        }
                        
                        console.log('Upload response (' + type + '):', rdata); // 调试信息
                        
                        if (typeof rdata === 'string') {
                            try {
                                rdata = JSON.parse(rdata);
                            } catch (e) {
                                console.error('JSON parse error:', e);
                                mdui.alert("上传失败！服务器返回了无效的响应格式");
                                return;
                            }
                        }
                        
                        if (rdata && rdata.code == 1) {
                            // 只更新对应的数据，不要覆盖其他数据
                            if (type === 'images' && rdata.images) {
                                uploadedImages = rdata.images;
                                console.log('Images uploaded:', uploadedImages);
                            } else if (type === 'video' && rdata.video) {
                                uploadedVideo = rdata.video;
                                console.log('Video uploaded:', uploadedVideo);
                            }
                            
                            mdui.snackbar({
                                message: rdata.msg,
                                position: 'right-top',
                            });
                            
                            // 更新预览显示状态
                            updatePreviewStatus();
                        } else {
                            console.error('Upload error:', rdata);
                            mdui.alert(rdata ? rdata.msg : "上传失败！未知错误");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', {xhr: xhr, status: status, error: error});
                        $("#isLoading").hide(100);
                        // 重新启用对应的按钮
                        if (type === 'images') {
                            $('#upload-images').removeAttr("disabled");
                        } else {
                            $('#upload-video').removeAttr("disabled");
                        }
                        
                        var errorMsg = "上传文件时出现错误！";
                        if (status === 'timeout') {
                            errorMsg = "上传超时！请检查文件大小或网络连接。";
                        } else if (xhr.status === 413) {
                            errorMsg = "文件过大！请选择较小的文件。";
                        } else if (xhr.status === 404) {
                            errorMsg = "上传接口不存在！请联系管理员。";
                        } else if (xhr.status === 500) {
                            errorMsg = "服务器内部错误！请联系管理员。";
                        }
                        
                        mdui.snackbar({
                            message: errorMsg,
                            position: 'top',
                            timeout: 5000
                        });
                    }
                });
            });
        }

        function updatePreviewStatus() {
            // 更新图片预览状态
            if (uploadedImages.length > 0) {
                $('#image-preview .upload-status').show();
                $('#image-count-info').html('<span style="color: #4CAF50;">✓ 已上传 ' + uploadedImages.length + ' 张图片</span>');
            }
            
            // 更新视频预览状态
            if (uploadedVideo) {
                $('#video-preview .mdui-progress').replaceWith(
                    '<div style="display: flex; align-items: center; color: #4CAF50; font-size: 12px; margin-top: 8px;">' +
                    '<i class="mdui-icon material-icons" style="font-size: 16px; margin-right: 4px;">check_circle</i> 已上传' +
                    '</div>'
                );
                $('#video-info').html('<span style="color: #4CAF50;">✓ 视频已上传</span>');
            }
        };

        // 重置图片选择状态
        function resetImageSelection() {
            // 清空文件选择
            $('#upload-images').val('');
            // 重置UI状态
            $('#image-count-info').text('未选择图片');
            $('#image-preview').empty().hide(); // 隐藏预览区域
            // 清空已上传的图片数据
            uploadedImages = [];
        }

        // 重置视频选择状态
        function resetVideoSelection() {
            // 清空文件选择
            $('#upload-video').val('');
            // 重置UI状态
            $('#video-info').text('未选择视频');
            $('#video-preview').empty().hide(); // 隐藏预览区域
            // 清空已上传的视频数据
            uploadedVideo = null;
        }

        function request(vCode) {
            var contact = $("#qq").val();
            var name = $("#name").val();
            var taName = $("#taName").val();
            var taClass = $("#taClass").val();
            var image = $("#image").val();
            var introduceTA = $("#introduceTA").val();
            var toTA = $("#toTA").val();
            var className = $("#class").val();
            
            // 客户端验证
            if (!name || !taName || !introduceTA || !toTA) {
                mdui.alert('请填写所有必填字段！');
                return;
            }
            
            var timestamp = this.timestamp = Date.parse(new Date()) / 1000;
            var key = $.md5(
                'Kagamine Yes!' +
                contact +
                name +
                taName +
                taClass +
                image +
                (uploadedImages ? uploadedImages.join(',') : '') +
                (uploadedVideo || '') +
                introduceTA +
                toTA +
                className +
                timestamp);
            
            requestApi("submit", {
                key: key,
                timestamp: timestamp,
                contact: contact,
                name: name,
                taName: taName,
                taClass: taClass,
                image: image,
                images: uploadedImages,
                video: uploadedVideo,
                introduceTA: introduceTA,
                toTA: toTA,
                class: className,
                vCode: vCode
            }, function(rdata) {
                // 只有成功时才清空表单和跳转
                if (rdata.code == 1) {
                    $("#qq").val("");
                    $("#name").val("");
                    $("#taName").val("");
                    $("#taClass").val("");
                    $("#image").val("");
                    $("#introduceTA").val("");
                    $("#toTA").val("");
                    $("#class").val("");
                    uploadedImages = [];
                    uploadedVideo = null;
                    $('#image-preview, #video-preview').empty().hide();
                    redirect_url = <?php
                        if ($REWRITE) {
                            echo "'/card/'+rdata.id";
                        } else {
                            echo "'/?page=card&id='+rdata.id";
                        }
                        ?>

                    $.pjax({
                        url: redirect_url,
                        container: '#pjax-container'
                    });
                }
            }, true, false, "#submitbtn")
        }
    </script>
</div>
<br /><br />