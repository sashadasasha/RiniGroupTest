<!DOCTYPE html>
<html lang="xyz">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Wordstat парсер</title>
</head>
<body>
  <form id="formElem" action="./wordstat.php" method="POST">
  <textarea name="words" rows="50" cols="50"></textarea>
  <div class="regionChoose">
  Все<input type="radio" name="region" value="all">
  Москва<input type="radio" name="region" value="Moscow">
  Дубна<input type="radio" name="region" value="Dubna">
  </div>
  <div class="submit-btn"><button type="submit">Отправить запрос</button></div>
  </form>
  <div class="report"></div>
  <script src="main.js"></script>
</body>
</html>