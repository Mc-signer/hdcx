<?php
require_once('config/init.php');
require_once("../PHPExcel-1.8/Classes/PHPExcel.php");
$objPHPExcel = new PHPExcel();
//选择sheet
$objPHPExcel->setActiveSheetIndex(0);
//设置sheet的name
$objPHPExcel->getActiveSheet()->setTitle('团队');
//设置默认字体
$objPHPExcel->getDefaultStyle()->getFont()->setName('宋体');
//设置默认水平垂直居中
$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//合并单元格
$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
$objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
$objPHPExcel->getActiveSheet()->mergeCells('C1:C2');
$objPHPExcel->getActiveSheet()->mergeCells('D1:D2');
$objPHPExcel->getActiveSheet()->mergeCells('E1:E2');
$objPHPExcel->getActiveSheet()->mergeCells('F1:F2');
$objPHPExcel->getActiveSheet()->mergeCells('G1:G2');
$objPHPExcel->getActiveSheet()->mergeCells('H1:H2');
$objPHPExcel->getActiveSheet()->mergeCells('I1:I2');
$objPHPExcel->getActiveSheet()->mergeCells('J1:J2');
$objPHPExcel->getActiveSheet()->mergeCells('K1:S1');
$objPHPExcel->getActiveSheet()->mergeCells('T1:T2');
//设置表头单元格值
$objPHPExcel->getActiveSheet()->setCellValue('A1', '注册账号');
$objPHPExcel->getActiveSheet()->setCellValue('B1', '大赛名称');
$objPHPExcel->getActiveSheet()->setCellValue('C1', '团队名称');
$objPHPExcel->getActiveSheet()->setCellValue('D1', '项目名称');
$objPHPExcel->getActiveSheet()->setCellValue('E1', '项目简介');
$objPHPExcel->getActiveSheet()->setCellValue('F1', '负责人姓名');
$objPHPExcel->getActiveSheet()->setCellValue('G1', '负责人联系方式');
$objPHPExcel->getActiveSheet()->setCellValue('H1', '指导教师姓名');
$objPHPExcel->getActiveSheet()->setCellValue('I1', '指导教师联系方式');
$objPHPExcel->getActiveSheet()->setCellValue('J1', '团队成员人数');
$objPHPExcel->getActiveSheet()->setCellValue('K1', '全部成员及信息');
$objPHPExcel->getActiveSheet()->setCellValue('K2', '序号');
$objPHPExcel->getActiveSheet()->setCellValue('L2', '姓名');
$objPHPExcel->getActiveSheet()->setCellValue('M2', '联系方式');
$objPHPExcel->getActiveSheet()->setCellValue('N2', '院系');
$objPHPExcel->getActiveSheet()->setCellValue('O2', '班级');
$objPHPExcel->getActiveSheet()->setCellValue('P2', '学号');
$objPHPExcel->getActiveSheet()->setCellValue('Q2', '身份证号');
$objPHPExcel->getActiveSheet()->setCellValue('R2', '电子邮箱');
$objPHPExcel->getActiveSheet()->setCellValue('S2', '年龄');
$objPHPExcel->getActiveSheet()->setCellValue('T1', '是否提交附件');
//设置单元格宽度
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(26.33);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10.89);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15.33);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13.11);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(17.56);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(17.56);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(4.78);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(6.78);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(11.89);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10.89);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10.89);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10.89);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(11.78);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(14.89);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(8.22);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(13.11);

//输出比赛信息
$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
$mysqli->query("set names utf8");
$result=$mysqli->query("select * from games where id = '{$_GET['gameId']}'");
$row=$result->fetch_array(MYSQLI_ASSOC);
$gameName=$row['name'];
$result=$mysqli->query("select games.name as gameName, users.name as userName,teams.* from teams inner join users inner join games where teams.game_id='".$_GET['gameId']."' and teams.user_id=users.id and games.id = '".$_GET['gameId']."' and teams.status != '0' and teams.status != '-1'");
$rows=$result->fetch_all(MYSQLI_ASSOC);
$i=3;
foreach ($rows as $key => $value) {
	$result1=$mysqli->query("select * from members where team_id='".$value['id']."'");
	$num=$result1->num_rows;
	for($j='A';$j<'K';$j++){
		$objPHPExcel->getActiveSheet()->mergeCells($j.$i.':'.$j.($i+($num?$num:1)-1));
	}
	$objPHPExcel->getActiveSheet()->mergeCells('T'.$i.':'.'T'.($i+($num?$num:1)-1));

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $value['userName']);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['gameName']);
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $value['team_name']);
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['pro_name']);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['pro_intro']);
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['pri_name']);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['pri_contact']);
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['teach_name']);
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $value['teach_contact']);
	$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $num);
	$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $value['attachment']);
	$rows1=$result1->fetch_all(MYSQLI_ASSOC);
	foreach ($rows1 as $key1 => $value1) {
		$objPHPExcel->getActiveSheet()->setCellValue('K'.($i+$key1), $key1);
		$objPHPExcel->getActiveSheet()->setCellValue('L'.($i+$key1), $value1['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('M'.($i+$key1), $value1['contact']);
		$objPHPExcel->getActiveSheet()->setCellValue('N'.($i+$key1), $value1['insititute']);
		$objPHPExcel->getActiveSheet()->setCellValue('O'.($i+$key1), $value1['class']);
		$objPHPExcel->getActiveSheet()->setCellValue('P'.($i+$key1), $value1['stunum']);
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.($i+$key1), $value1['idcard']);
		$objPHPExcel->getActiveSheet()->setCellValue('R'.($i+$key1), $value1['email']);
		$objPHPExcel->getActiveSheet()->setCellValue('S'.($i+$key1), $value1['age']);
	}

	$i+=$num;
}
$mysqli->close();
//输出到浏览器
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
header("Content-Type:application/force-download");
header("Content-Type:application/vnd.ms-execl");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");;
header("Content-Disposition:attachment;filename='".$gameName.".xls'");
header("Content-Transfer-Encoding:binary");
$objWriter->save('php://output');