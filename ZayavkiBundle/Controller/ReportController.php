<?php


namespace Acme\ZayavkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\ZayavkiBundle\Model\Resanswer;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;


class ReportController extends Controller
{
	public function ticketsAction($params, Request $request)
	{
		$excel = new \PHPExcel();
		$excel->setActiveSheetIndex(0);
		$sht = $excel->getActiveSheet();
        $excel->getDefaultStyle()->getFont()->setName('Calibri')->setBold(false)->setSize(12);
        $sht->getDefaultRowDimension()->setRowHeight(15);
        $sht->setCellValue('B2', 'Список заявок');
        $sht->getStyle('B2')->getFont()->setName('Calibri')->setBold(true)->setSize(14);

		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$objWriter->save('/var/www/tmp/demo.xls');		
		
 // Redirect output to a client’s web browser (Excel5)
		// $response = new Response();
		// $response->headers->set('Content-Type', 'application/vnd.ms-excel');
		// $response->headers->set('Content-Disposition', 'attachment;filename="demo.xls"');
		// $response->headers->set('Cache-Control', 'max-age=0');	
		
		// $response->prepare($request);
		// $response->sendHeaders();
		// $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		// $objWriter->save('php://output');
		exit();	
	}
	
	public function setHat($oExcel, $caption, $cols) 
	{
		$sht = $oExcel->getActiveSheet();
		$oExcel->getDefaultStyle()->getFont()->setName('Calibri')->setBold(false)->setSize(12);
		$nr = 2;
		$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(1).$nr, $caption);
		//$sht->mergeCells('B'.$nr.':M'.$nr);
		$nr++;
		
		$i = 1;
		foreach($cols as $col) {
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex($i).$nr, $col);
			$i++;
		}
		$sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(1).$nr.':'.PHPExcel_Cell::stringFromColumnIndex($i-1).$nr)->getBorders()
				->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(1).$nr.':'.PHPExcel_Cell::stringFromColumnIndex($i-1).$nr)->getBorders()				
				->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(1).$nr.':'.PHPExcel_Cell::stringFromColumnIndex($i-1).$nr)->getBorders()				
				->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(1).$nr.':'.PHPExcel_Cell::stringFromColumnIndex($i-1).$nr)->getBorders()				
				->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);	
		$nr++;
		return $nr;
	}
	
	public function setFooter($oExcel) 
	{
    	for ($i = 0; $i <= 15; $i++) {
            $cell = substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $i, 1);
            $oExcel->getActiveSheet()->getColumnDimension($cell)->setAutoSize(true);
	    }
	}	
	
	public function rptticketsAction($params, Request $request)
	{
		$params = json_decode($params, true);
		$user = $this->get('security.context')->getToken()->getUser();
			 
		$list = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->getTicketList( $params, $array(
				  'sort'  => $params['sort'],
				  'order' => $params['order'],
				  'offset'=> 0, 
				  'to'    => 99999999,
				  'head'  => 1, 
				  'userid'=> $user->getId()
				  );
				);		
		
		
		$oExcel = new \PHPExcel();
		$oExcel->setActiveSheetIndex(0);		
   		$sht = $oExcel->getActiveSheet();
		
		$oExcel->getProperties()->setCreator("Dispatcher")
                   ->setLastModifiedBy("Someone")
                   ->setTitle("Отчет")
                   ->setSubject("Отчет");
		// Header
		$nr = $this->setHat($oExcel, 'Список заявок ', array('Номер заявки','№ квартиры','Описание ЗАЯВКИ (неисправности)','ТЕЛЕФОН СОБСТВЕННИКА','ФИО ДИСПЕТЧЕРА ОБЪЕКТА, принявшего заявку',
		'ДАТА И ВРЕМЯ ПОСТУПЛЕНИЯ ЗАЯВКИ ДИСПЕТЧЕРУ ОБЪЕКТА','ДАТА И ВРЕМЯ ПЕРЕДАЧИ ЗАЯВКИ ИСПОЛНИТЕЛЮ','ФИО ИСПОЛНИТЕЛЯ заявки',
		'ОТМЕТКА ОБ УСТРАНЕНИИ (ДАТА И ВРЕМЯ) (в работе, деталь заказана и др.)','ДАТА И ВРЕМЯ СООБЩЕНИЯ ЗАЯВИТЕЛЮ об устранении','Примечание'));
		
       	foreach ($rlis as $rs){
		    $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(1).$nr, $rs['nr']);
		    $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(2).$nr, $rs['kv']);
		    $sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(2).$nr)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(3).$nr, $rs['descr']);
            $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(4).$nr, $rs['phone']);
            $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(5).$nr, $rs['disp']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(6).$nr, $rs['dstart']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(7).$nr, $rs['dwork']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(8).$nr, $rs['wname']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(9).$nr, $rs['sname'].'     '.$rs['dstop']);
		
		    $nr++;
    	}			
		$this->setFooter($oExcel);
		
		$objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel5');
		$objWriter->save('/var/www/tmp/demo2.xls');	
		exit();			
	}
	
	public function rptusersAction(Request $request)
	{
		$oExcel = new \PHPExcel();
		$oExcel->setActiveSheetIndex(0);		
   		$sht = $oExcel->getActiveSheet();
		
		$oExcel->getProperties()->setCreator("Dispatcher")
                   ->setLastModifiedBy("Someone")
                   ->setTitle("Отчет")
                   ->setSubject("Отчет");
		// Header
		$nr = $this->setHat($oExcel, 'Список пользователей системы ', array('Организация', 'Пользователь','Логин','Отключен'));		
		$tsgid = -1;
		$res =  $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->getRptusers();		
       	foreach ($res as $rs){
		    $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(1).$nr, (($tsgid <> $rs['tsgid'])?$rs['tsgname']:''));
		    $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(2).$nr, $rs['name']);
		    $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(3).$nr, $rs['username']);
		    $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(4).$nr, (($rs['deleted'] == 1)?'ДА':''));
			$sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(1).$nr)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(2).$nr)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(3).$nr)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(4).$nr)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);		
		    $tsgid = $rs['tsgid'];
		    $nr++;
    	}			
		$this->setFooter($oExcel);
		
		$objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel5');
		$objWriter->save('/var/www/tmp/demo2.xls');	
		exit();			
	}	
	
	public function rpttotalAction($d1, $d2, Request $request)
	{
		$oExcel = new \PHPExcel();
		$oExcel->setActiveSheetIndex(0);		
   		$sht = $oExcel->getActiveSheet();
		
		$oExcel->getProperties()->setCreator("Dispatcher")
                   ->setLastModifiedBy("Someone")
                   ->setTitle("Отчет")
                   ->setSubject("Отчет");
		// Header
		$nr = $this->setHat($oExcel, 'Сводная таблица по заявкам по всем организациям за период c '.$d1.' по '.$d2, 
									array('Организация','Начало','Поступило','Выполнено','Отменено','в т.ч. просрочено','Конец'));
	
		$res =  $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->getRpttotal($d1, $d2);		
       	foreach ($res as $rs){	
		
		    $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(1).$nr, $rs['name']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(2).$nr, $rs['v1']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(2).$nr, $rs['v2']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(2).$nr, $rs['v3']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(2).$nr, $rs['v4']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(2).$nr, $rs['v6']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(2).$nr, $rs['v5']);
		    $nr++;
    	}			
		
		$l = $nr-1;
		$sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(1).$nr.':'.PHPExcel_Cell::stringFromColumnIndex(7).$nr)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(1).$nr.':'.PHPExcel_Cell::stringFromColumnIndex(7).$nr)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(1).$nr.':'.PHPExcel_Cell::stringFromColumnIndex(7).$nr)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(1).$nr.':'.PHPExcel_Cell::stringFromColumnIndex(7).$nr)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	    $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(1).$nr, 'Итого:');
		for ($i = 2; $i <= 7; $i++) {
			$col = PHPExcel_Cell::stringFromColumnIndex($i);
			$sht->setCellValue($col.$nr, '=SUM('.$col.'4:'.$col.$l.')');
		}
				
		$this->setFooter($oExcel);
		
		$objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel5');
		$objWriter->save('/var/www/tmp/demo3.xls');	
		exit();			
	}		
	
}