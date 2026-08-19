<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $USER;
CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

$catalogId = 5;

$propArticle = 'CML2_ARTICLE';

$warehousingInfo = [];


$warehousingInfo[] = [
  "NAME" => '1c', // имя папки
  "ID" => 2, // id склад
  "CELL_ARTICLE" => 'A', // ячейка артикул
  "CELL_BALANCE" => 'H' // ячейка баланс
];

$warehousingInfo[] = [
   "NAME" => 'mdd', // имя папки
   "ID" => 4, // id склад
  "CELL_ARTICLE" => 'A', // ячейка артикул
  "CELL_BALANCE" => 'G' // ячейка баланс
 ];

$warehousingInfo[] = [
   "NAME" => 'santop', // имя папки
   "ID" => 3, // id склад
  "CELL_ARTICLE" => 'A', // ячейка артикул
  "CELL_BALANCE" => 'E' // ячейка баланс
 ];

$warehousingInfo[] = [
   "NAME" => 'santel', // имя папки
   "ID" => 5, // id склад
  "CELL_ARTICLE" => 'A', // ячейка артикул
  "CELL_BALANCE" => 'F' // ячейка баланс
 ];

$warehousingInfo[] = [
  "NAME" => 'santex', // имя папки
  "ID" => 7, // id склад
  "CELL_ARTICLE" => 'B', // ячейка артикул
  "CELL_BALANCE" => 'J' // ячейка баланс
];

	//set_include_path($_SERVER['DOCUMENT_ROOT'].'/local/templates/main/ajax/Classes/');
	set_include_path('Classes/');
	include 'PHPExcel/IOFactory.php';

foreach ($warehousingInfo as $key => $warehousing) {

  if(!$warehousing["NAME"] || !$warehousing["ID"] || !$warehousing["CELL_ARTICLE"] || !$warehousing["CELL_BALANCE"]) continue;


  $warehousingName = $warehousing["NAME"];
  $warehousingId = $warehousing["ID"];
  $cellArticle = $warehousing["CELL_ARTICLE"];
  $cellBalance = $warehousing["CELL_BALANCE"];





  $pathFile = '/local/php_interface/handler_balance/xls/'.$warehousingName;

  $articleListNew = [];

  //START получаем список из HL ArticlesList

    $articleList = [];
    $articleListOld = [];

    $entity_data_class = getHlInfo::class( "ArticlesList" );

    $rsData = $entity_data_class::getList([
      "select" => ['UF_ARTICLE'],
      "order" => ["ID" => "ASC"],
    ]);
    while($arData = $rsData->fetch()){
      $articleList[$arData["UF_ARTICLE"]] = 0; 
      $articleListOld[] = $arData["UF_ARTICLE"]; 
    }

  //END получаем список из HL ArticlesList






  $file_time=array();
  $file_name=array();
  if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].$pathFile)) {
      while (false !== ($file = readdir($handle))) {        
          $format=array();
          $format=explode('.', $file);
          if($format[1]=='xls'){
              $file_name=$file;
              $time=date('H:i:s',filectime($_SERVER['DOCUMENT_ROOT'].$pathFile.'/'.$file));
              $file_time[$file] = $file;
              //break;
          }
      }
      krsort($file_time);
      foreach ($file_time as $arFile_name) {
          $file_name=$arFile_name;
           //$arrFile_name[]=$arFile_name;
          //break;
      }



      closedir($handle); 

    

  }


  //print_r($arrFile_name);

  if(strlen($file_name)>0) {





        $file_url = $_SERVER['DOCUMENT_ROOT'] . $pathFile.'/' . $file_name;
        $file_url = urldecode($file_url);

        $inputFileName = $file_url;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        $relations = [];
        $brands = [];
        $items = [];
        $k = 0;

        //Заполнение отношений
        // foreach($sheetData[1] as $index => $title) {
        //   if(array_key_exists($title, $configCols)) {
        //       $relations[$index] = $configCols[$title];
        //   }
        // }



          //Заполнение данных
         foreach ($sheetData as $arItem) {
              $k++;
              if($arItem[$cellArticle]){

                if(!in_array($arItem[$cellArticle], $articleListOld)) $articleListNew[] = $arItem[$cellArticle];

                $articleList[$arItem[$cellArticle]] = $arItem[$cellBalance];

                

              } 



              // if ($k > 1) {

              // }
         }

  ?><pre>
  <?print_r($articleList)?>
    </pre><?
  ?><pre>
  <?print_r($articleListNew)?>
    </pre><?


        unlink ($file_url);

        if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].$pathFile)) {
            while (false !== ($file = readdir($handle))) { 
                $format=array();
                $format=explode('.', $file);
                if($format[1]=='xls'){
                    unlink ($_SERVER['DOCUMENT_ROOT'].$pathFile.'/'.$file);
                }
            }
            closedir($handle); 
        }

   



  } else {
      ?>
      Файл не найден
      <?
  }


}
?>