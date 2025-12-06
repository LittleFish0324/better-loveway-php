-- 数据库升级脚本
-- 用于从旧版本升级到最新版本
-- 请在导入前先备份现有数据库

-- 添加缺失的字段（如果不存在的话）

-- 添加表白者班级字段
ALTER TABLE `loveway_data` 
ADD COLUMN `class` varchar(10) DEFAULT '' COMMENT '表白者班级' AFTER `contact`;

-- 添加被表白者班级字段  
ALTER TABLE `loveway_data`
ADD COLUMN `ta_class` varchar(10) DEFAULT '' COMMENT '被表白者班级' AFTER `to_who`;

-- 添加多图片字段
ALTER TABLE `loveway_data`
ADD COLUMN `images` text COMMENT '多张图片，逗号分隔' AFTER `image`;

-- 添加视频字段
ALTER TABLE `loveway_data`
ADD COLUMN `video` text COMMENT '视频地址' AFTER `images`;

-- 更新字符集为utf8mb4以支持更多字符和表情
ALTER TABLE `loveway_config` CONVERT TO CHARACTER SET utf8mb4;
ALTER TABLE `loveway_data` CONVERT TO CHARACTER SET utf8mb4;

-- 为新字段添加注释
ALTER TABLE `loveway_data` 
MODIFY COLUMN `confessor` text NOT NULL COMMENT '表白者姓名',
MODIFY COLUMN `contact` text NOT NULL COMMENT '联系方式',
MODIFY COLUMN `time` text NOT NULL COMMENT '提交时间',
MODIFY COLUMN `to_who` text NOT NULL COMMENT '被表白者姓名',
MODIFY COLUMN `introduction` text NOT NULL COMMENT '介绍语',
MODIFY COLUMN `content` text NOT NULL COMMENT '表白内容',
MODIFY COLUMN `image` text DEFAULT '' COMMENT '单张图片',
MODIFY COLUMN `comment` text NOT NULL DEFAULT '[]' COMMENT '评论数据，JSON格式';

-- 升级完成提示
SELECT '数据库升级完成！新功能包括：' AS status;
SELECT '- 表白者和被表白者班级字段' AS features;
SELECT '- 多图片和视频上传支持' AS features;
SELECT '- 敏感词检测功能' AS features;
SELECT '- 高级搜索功能' AS features;