<?php
function export_csv($filename, $data, $columns, $chunk = 1000000)
{
    header('Content-Type: application/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
    header('Cache-Control: max-age=0');

    $prefix = str_random(10);

    $fileList = []; // 文件集合
    $fileList[] = $file = storage_path("app/public/${prefix}_${filename}_1.csv");

    $fp = fopen($file, 'w');
    fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
    $head = array_pluck($columns, 'title');
    fputcsv($fp, $head);

    // 计数器
    $i = 0;
    // 每隔$limit行刷新一下输出buffer，不要太大，也不要太小
    $limit = 10000;
    // 行上限
    $maxLimit = 100000000;

    foreach ($data as $item) {
        if ($i >= $maxLimit) {
            break;
        }

        if ($i > 0 && $i % $chunk == 0) {
            fclose($fp);  // 关闭上一个文件
            $j = $i / $chunk + 1;
            $fileList[] = $file = storage_path("app/public/${prefix}_${filename}_$j.csv");

            $fp = fopen($file, 'w');
            fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($fp, $head);
        }

        $i++;

        if ($i % $limit == 0) {
            ob_flush();
            flush();
        }

        $row = [];

        foreach ($columns AS $column) {
            $value = isset($column['index']) ? $item->{$column['index']} : null;
            $render = array_get($column, 'render');
            if ($render && $render instanceof Closure) {
                $row[] = $render($value, $item);
            } else {
                if (is_numeric($value) && strlen($value) > 10) {
                    $value .= "\t";
                }
                $row[] = $value;
            }
        }

        fputcsv($fp, $row);
        unset($row);
    }

    fclose($fp);

    if (count($fileList) > 1) {
        $zip = new ZipArchive();
        $oldFilename = $filename;
        $filename = storage_path("app/public/${prefix}_${filename}.zip");
        $zip->open($filename, ZipArchive::CREATE); // 打开压缩包

        foreach ($fileList as $file) {
            $zip->addFile($file, str_replace("${prefix}_", '', basename($file)));   // 向压缩包中添加文件
        }
        $zip->close(); // 关闭压缩包

        foreach ($fileList as $file) {
            @unlink($file); // 删除csv临时文件
        }

        // 输出压缩文件提供下载
        header("Cache-Control: max-age=0");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename=' . $oldFilename . '.zip');
        header("Content-Type: application/zip"); // zip格式的
        header("Content-Transfer-Encoding: binary");
        header('Content-Length: ' . filesize($filename));
        @readfile($filename);//输出文件;
        @unlink($filename); //删除压缩包临时文件
    } else {
        $filename = head($fileList);
        @readfile($filename);
        @unlink($filename); // 删除压缩包临时文件
    }

    exit;
}

$sql = "SELECT * FROM users";
$users = DB::cursor($sql);
$columns = [
    [
        'title' => '用户ID',
        'index' => 'id',
    ],
    [
        'title' => '用户名称',
        'index' => 'name',
    ],
    [
        'title' => '电子邮箱',
        'index' => 'email',
    ],
    [
        'title' => '注册时间',
        'index' => 'created_at',
    ],
];

export_csv('用户列表', $users, $columns);