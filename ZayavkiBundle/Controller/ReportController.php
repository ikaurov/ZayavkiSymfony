<?php


namespace Acme\ZayavkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Acme\ZayavkiBundle\Model\Resanswer;
use Acme\ZayavkiBundle\Form\Type\Report2Type;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;


class ReportController extends Controller
{
	public function report_par2Action()
	{	
		$data = array( 'translate' => $this->get('transloc')->getTranslated('S') );
		$form = $this->createForm(new Report2Type(), $data);
	
		$d1 = '01.02.2014';
		$d2 = '12.02.2014';
	
		return $this->render('AcmeZayavkiBundle:Default:report2param.html.twig', array(
			'form' => $form->createView(),
			'd1' => $d1,
			'd2' => $d2
		));	
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
	
	public function setFooter($oExcel, $filename, $request) 
	{
    	for ($i = 0; $i <= 15; $i++) {
            $cell = substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $i, 1);
            $oExcel->getActiveSheet()->getColumnDimension($cell)->setAutoSize(true);
	    }
		
		$response = new Response();
		$response->headers->set('Content-Type', 'application/vnd.ms-excel');
		$response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
		$response->headers->set('Cache-Control', 'max-age=0');	
		
		$response->prepare($request);
		$response->sendHeaders();
		$objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel5');
		$objWriter->save('php://output');				
	}	
	
	public function rptticketsAction($params, Request $request)
	{
		if ($request->getMethod() == 'POST') {
			$var = $request->request->all();
		} else {
			$var = array('reports_filter' => '');
		}
		
		$params = json_decode($var['reports_filter'], true);
		
		$user = $this->get('security.context')->getToken()->getUser();
		
		
		$list = $this->getDoctrine()->getRepository('AcmeZayavkiBundle:Tickets')->getTicketList( $params, array(
				  'sort'  => 'nr',//$params['sort'],
				  'order' => 'ASC',//$params['order'],
				  'offset'=> 0, 
				  'to'    => 99999999,
				  'head'  => 1, 
				  'userid'=> $user->getId()
				  )
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
		
       	foreach ($list['rows'] as $rs){
		    $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(1).$nr, $rs['nr']);
		    $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(2).$nr, $rs['kv']);
		    $sht->getStyle(PHPExcel_Cell::stringFromColumnIndex(2).$nr)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		    $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(3).$nr, $rs['message']);
            $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(4).$nr, $rs['phone']);
            $sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(5).$nr, $rs['disp']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(6).$nr, $rs['dstart']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(7).$nr, $rs['dplan']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(8).$nr, $rs['wname']);
			$sht->setCellValue(PHPExcel_Cell::stringFromColumnIndex(9).$nr, $rs['sname'].'     '.$rs['dfact']);
		
		    $nr++;
    	}			
			$this->setFooter($oExcel, 'Список_заявок_'.date('d-m-Y').'.xls', $request);		

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

		$this->setFooter($oExcel, 'Cписок_пользователей_'.date('d-m-Y').'.xls', $request);	
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
				
		$this->setFooter($oExcel, 'Итоговый_отчет_'.date('d-m-Y').'.xls', $request);
		exit();			
	}		
	
}