<?php
$words = trim(htmlspecialchars(strip_tags(stripslashes($_POST['words']))));
$words = str_replace(["\r\n", ", ", " ,", ","], "\n", $words);

$wordsArray = explode("\n", $words);
$regionValue = trim(htmlspecialchars(strip_tags(stripslashes($_POST['region']))));

#Определение региона показов
switch ($regionValue) {
  case "all": $regionId = [1];
  break;
  case "Moscow": $regionId = [213];
  break;
  case "Dubna": $regionId = [215];
  default: $regionId = [1];
  break;
}

#AgAAAAATFXhZAAXgZe0ZMs06h0PgoNZWBLtiYOs - токен

function getWordstat($method, $params = false) {

  $request = array(
  'token'=> 'AgAAAAATFXhZAAXgZe0ZMs06h0PgoNZWBLtiYOs', 
  'method'=> $method,
  'param'=> is_array($params)? utf8($params):$params,
  'locale'=> 'ru',
);
     
$request = json_encode($request);
$opts = array(
  'http'=>array(
    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",	
    'method'=>"POST",
    'content'=>$request,
  )
); 

$context = stream_context_create($opts); 
$result = file_get_contents('https://api-sandbox.direct.yandex.ru/v4/json/ ', 0, $context);
return json_decode($result);
}

# перекодировка строковых данных в UTF-8
function utf8($struct) {
foreach ($struct as $key => $value) {
  if (is_array($value)) {
    $struct[$key] = utf8($value);
  }
  elseif (is_string($value)) {
    $struct[$key] = utf8_encode($value);
  }
}
return $struct;
}

#Если в запросе больше 10 фраз, то разбиваем их на подмассивы по 10 строк
$dividedArray = array ();
function divideArray (array $arr) {
  global $dividedArray;
  if (count($arr) > 10) {
    array_push($dividedArray, array_slice($arr, 0, 10));
    $tail = array_slice($arr, 10);
    if (count($tail) > 10) {
      divideArray ($tail);
    } else {
      array_push($dividedArray, $tail);
    }
  } else { 
    array_push($dividedArray, $arr);
  }
  return $dividedArray;
}
$wordsOnGroup = divideArray ($wordsArray);

#Получение результата
$result = [];
foreach ($wordsOnGroup as $value) {
  $request = getWordstat('CreateNewWordstatReport', array(
  'Phrases' => $value,
  'GeoID' => $regionId
)
  );
  sleep(5);
  array_push($result, getWordstat('GetWordstatReport', $request->data));	
  getWordstat('DeleteWordstatReport', $request->data);
}

$arrayForFile = array ();
for ($i = 0; $i < count($result); $i++) {
    for ($j = 0; $j < count($result[$i]->data); $j++) {
    $name = $result[$i]->data[$j]->SearchedWith[0]->Phrase;
    $shows = $result[$i]->data[$j]->SearchedWith[0]->Shows;
    $arrayForFile[$name] = $shows;
  }
}

#Запись в csv файл
  $fp = fopen('./report.csv', 'w');
  fputcsv($fp, ['Фраза', 'Просмотры']);
  foreach ($arrayForFile as $name => $shows) {
  fputcsv($fp, [$name, $shows]);
  }
  fclose($fp);
