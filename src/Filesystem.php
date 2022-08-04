<?php
/**
 * This file is part of the XCloud package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace XCloud\Component\Filesystem;

use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;


/**
 * Provides basic utility to manipulate the file system.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Filesystem extends SymfonyFilesystem
{
    protected $WIN_OS = false;
    protected $LC_CTYPE = 'C';

    public function __construct()
    {
        // dpkg-reconfigure locales
        // setlocale(LC_ALL, 'zh_CN.GBK');
        // setlocale(LC_CTYPE, 'zh_CN.UTF-8');


        $this->WIN_OS   = stripos(PHP_OS, 'WIN') !== false;
        $this->LC_CTYPE = setlocale(LC_CTYPE, 0);


        // echo '<pre>';
        // print_r($this->dirname('D:\Anmp\conf\\\\//简体中文呢/s中文\\a中文.md') . PHP_EOL);
        // print_r($this->basename('D:\Anmp\conf\\\\//简体中文呢/s中文\\a中文.md') . PHP_EOL);
        // print_r($this->pathinfo('D:\Anmp\conf\\\\//简体中文呢/s中文\\a中文.md'));

    }


    /**
     * 返回路径中的文件名部分
     *
     * @param string $path
     * @param null   $suffix
     *
     * @return string
     */
    public function basename($path, $suffix = null)
    {
        // TODO: Linux setlocale('zh_CN.GBK')

        $path     = preg_replace('#[/\\\\]+#', '/', $path);
        $basename = basename($path, $suffix);

        return $basename;
    }

    /**
     * 返回路径中的目录部分
     *
     * @param string $path
     *
     * @return string
     */
    public function dirname($path)
    {
        // TODO: Linux setlocale('zh_CN.GBK')

        $path = preg_replace('#[/\\\\]+#', '/', $path);

        return dirname($path);
    }

    /**
     * 返回文件路径的信息
     *
     * @param string $path
     * @param int    $options
     *
     * @return mixed
     */
    public function pathinfo($path, $options = 15)
    {
        // TODO: Linux setlocale('zh_CN.GBK')

        $path = preg_replace('#[/\\\\]+#', '/', $path);

        return pathinfo($path, $options);
    }


    /**
     * 返回规范化的绝对路径名
     *
     * @param string $path
     * @param bool   $real
     *
     * @return false|string
     */
    public function realpath($path, $real = false)
    {
        $path = preg_replace('#[/\\\\]+#', '/', $path);

        if ($this->WIN_OS) {
            $path = mb_convert_encoding($path, 'GBK', 'UTF-8');
        }

        $path = realpath($path);

        if (is_string($path)) {
            $path = str_replace('\\', '/', $path);

            if ($this->WIN_OS && !$real) {
                $path = mb_convert_encoding($path, 'UTF-8', 'GBK');
            }
        }

        return $path;
    }


    /**
     * 判断给定文件名是否可读
     *
     * @param $filename
     *
     * @return bool
     */
    public function canRead($filename)
    {
        if (false === $filename = $this->realpath($filename)) {
            return false;
        }

        return is_readable($filename);
    }

    /**
     * 判断给定的文件名是否可写
     *
     * @param $filename
     *
     * @return bool
     */
    public function canWrite($filename)
    {
        if (false === $filename = $this->realpath($filename)) {
            return false;
        }

        return is_writable($filename);
    }


    /**
     * @param $path
     *
     * @return mixed
     */
    public function securePathName($path)
    {
        $path = preg_replace('#[/\\\\]+#', '/', $path);
        $path = str_replace('../', '-no-secure-/', $path);

        return $path;
    }


    /**
     * 取得文件大小
     *
     * @param $filename
     *
     * @return int
     */
    public function filesize($filename)
    {
        if (false === $filename = $this->realpath($filename, true)) {
            return false;
        }

        return filesize($filename);
    }

    public function g2u($str)
    {
        return mb_convert_encoding($str, 'UTF-8', 'GBK');
    }

    public function u2g($str)
    {
        return mb_convert_encoding($str, 'GBK', 'UTF-8');
    }
}
