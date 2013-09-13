<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chipx
 * Date: 13.09.13
 * Time: 7:26
 * To change this template use File | Settings | File Templates.
 */
include_once '../../vendor/autoload.php';
class PHPWorker {
    protected $fileName;
    /**
     * @var PHPExcel
     */
    protected $phpExcel;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
        $this->initPhpExcel();
    }

    protected function initPhpExcel()
    {
        if (file_exists($this->fileName)) {
            $this->phpExcel = PHPExcel_IOFactory::load($this->fileName);
        } else {
            $this->phpExcel = new PHPExcel();
        }
    }

    public function write($row, $column, $data)
    {
        $this->phpExcel->getActiveSheet()->getCellByColumnAndRow($column, $row)->setValue($data);
    }

    public function save($file)
    {
        $writer = new PHPExcel_Writer_Excel2007($this->phpExcel);
        $writer->save($file);
    }
}

$w = new PHPWorker(__DIR__.'/data.xlsx');
$w->write(2,1, 'Hello world222');
$w->save(__DIR__.'/data.xlsx');