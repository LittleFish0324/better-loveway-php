# 表白墙系统安装说明

## 系统要求
- PHP 7.3+
- MySQL 5.7+
- Web服务器 (Apache/Nginx)
- 支持文件上传

## 安装步骤

### 1. 全新安装

```bash
# 1. 下载代码到网站目录
# 2. 配置config.php
# 3. 导入数据库结构
mysql -u用户名 -p密码 数据库名 < import.sql
```

### 2. 现有系统升级

```bash
# 1. 备份现有数据库
mysqldump -u用户名 -p密码 数据库名 > backup.sql

# 2. 升级数据库结构
mysql -u用户名 -p密码 数据库名 < upgrade.sql
```

## 功能特性

### 🎯 核心功能
- ✅ 表白信息发布
- ✅ 图片上传（单张/多张）
- ✅ 视频上传
- ✅ 班级信息（表白者&被表白者）
- ✅ 点赞功能
- ✅ 评论系统

### 🔍 高级功能
- ✅ 敏感词检测（自动过滤）
- ✅ 高级搜索（时间/内容/媒体类型）
- ✅ 响应式设计
- ✅ 验证码保护

### 🛡️ 安全特性
- ✅ 数据签名验证
- ✅ 文件大小限制
- ✅ 文件类型检查
- ✅ SQL注入防护

## 配置说明

### config.php 关键配置
```php
// 数据库配置
$DB_HOST = 'localhost';
$DB_USER = 'your_username';
$DB_PASS = 'your_password';
$DB_NAME = 'your_database';

// 功能开关
$UPLOAD_IMAGE = true;        // 是否允许图片上传
$IMAGE_VERIFICATION = true;   // 是否开启验证码

// 文件大小限制
$MAX_IMAGE_SIZE = 5;      // 图片最大尺寸(MB)
$MAX_VIDEO_SIZE = 100;     // 视频最大尺寸(MB)
```

## 敏感词配置

### 添加自定义敏感词
1. 编辑 `/banword/1.txt` 文件
2. 每行一个敏感词
3. 支持中文、英文、数字
4. 保存后立即生效

### 敏感词分类
- `ad.txt` - 违法广告
- `curse.txt` - 侮辱词汇  
- `drug.txt` - 毒品相关
- `gamble.txt` - 赌博信息
- `polity.txt` - 政治敏感
- `ponr.txt` - 色情内容
- `violence.txt` - 暴力内容
- `1.txt` - 自定义词汇

## 目录结构

```
├── api/                # API接口
│   ├── submit.php      # 提交接口
│   ├── favorite.php    # 点赞接口
│   └── ...
├── pages/             # 页面文件
│   ├── homepage.php    # 主页
│   ├── submit.php      # 提交页
│   └── card.php       # 详情页
├── includes/          # 包含文件
│   ├── function.php    # 公共函数
│   └── badword.php    # 敏感词检测
├── banword/           # 敏感词库
│   ├── 1.txt         # 自定义
│   ├── ad.txt         # 广告
│   └── ...
├── static/            # 静态资源
│   ├── uploads/        # 上传目录
│   └── ...
├── import.sql         # 完整数据库结构
├── upgrade.sql        # 升级脚本
└── config.php         # 配置文件
```

## 常见问题

### Q: 上传文件失败？
A: 检查：
1. `static/uploads/` 目录权限
2. PHP上传大小限制
3. 磁盘空间

### Q: 敏感词误报？
A: 编辑 `/banword/` 目录下的txt文件
删除误报词汇或添加白名单

### Q: 搜索不工作？
A: 检查：
1. 数据库连接
2. `homepage.php` 中的搜索SQL
3. 字符集设置

### Q: 签名验证失败？
A: 检查：
1. `$VERIFICATION_KEY` 配置
2. 前后端时间同步
3. 网络传输完整性

## 更新日志

### v2.0 (最新)
- ✨ 添加被表白者班级字段
- ✨ 多图片和视频上传
- ✨ 敏感词自动检测
- ✨ 高级搜索功能
- 🐛 修复文件大小验证
- 🐛 修复签名验证问题
- 🐛 优化UI响应式设计

### v1.0
- 🎉 基础表白墙功能
- 🎉 点赞和评论
- 🎉 验证码保护

## 技术支持

- 前端：MDUI + jQuery
- 后端：PHP 7.3+
- 数据库：MySQL 5.7+
- 样式：响应式设计

## 安全提示

1. 定期备份数据库
2. 更新敏感词库
3. 监控上传文件
4. 设置合理的文件大小限制
5. 启用HTTPS加密传输

---

**安装完成后即可使用所有功能！🎉**