<?php
/**
 * 敏感词检测函数
 * @param string $text 要检测的文本
 * @param array $customWords 自定义敏感词数组（可选）
 * @return array 包含检测结果和违规词汇
 */
function checkBadWords($text, $customWords = []) {
    // 收集所有敏感词
    $badWords = [];
    
    // 读取banword目录下的所有txt文件
    $banwordDir = __DIR__ . '/../banword';
    if (is_dir($banwordDir)) {
        $files = glob($banwordDir . '/*.txt');
        foreach ($files as $file) {
            $content = file_get_contents($file);
            if ($content !== false) {
                // 按行分割，移除空行和空白字符
                $words = array_filter(array_map('trim', explode("\n", $content)));
                $badWords = array_merge($badWords, $words);
            }
        }
    }
    
    // 添加自定义敏感词
    if (!empty($customWords)) {
        $badWords = array_merge($badWords, $customWords);
    }
    
    // 去重并过滤空值
    $badWords = array_unique(array_filter($badWords));
    
    // 检测敏感词
    $foundWords = [];
    $lowerText = strtolower($text);
    
    foreach ($badWords as $word) {
        $word = trim($word);
        if (empty($word)) continue;
        
        // 不区分大小写检测
        if (strpos($lowerText, strtolower($word)) !== false) {
            $foundWords[] = $word;
        }
    }
    
    return [
        'hasBadWord' => !empty($foundWords),
        'badWords' => array_unique($foundWords),
        'totalWords' => count($badWords)
    ];
}

/**
 * 格式化敏感词检测结果为用户友好的消息
 * @param array $result 检测结果
 * @return string 格式化的消息
 */
function formatBadWordMessage($result) {
    if (!$result['hasBadWord']) {
        return '';
    }
    
    $badWords = $result['badWords'];
    $wordCount = count($badWords);
    
    if ($wordCount === 1) {
        return "提交内容包含敏感词：「" . $badWords[0] . "」。请修改后重新提交。";
    } else if ($wordCount <= 3) {
        return "提交内容包含敏感词：" . implode('、', $badWords) . "。请修改后重新提交。";
    } else {
        return "提交内容包含" . $wordCount . "个敏感词：" . implode('、', array_slice($badWords, 0, 3)) . "等。请修改后重新提交。";
    }
}
?>