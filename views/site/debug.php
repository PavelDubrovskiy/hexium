<b>Ошибка в SQL-запросе:</b> <?=$sql?><br />
<b>Ошибка вызвана:</b>
<table border="1">
	<tr><th style="padding: 5px;">Файл</th><th style="padding: 5px;">Линия</th><th style="padding: 5px;">Класс</th><th style="padding: 5px;">Функция</th></tr>
<?foreach($backtrace as $item):?>
	<tr>
		<td style="padding: 5px;"><?=$item['file']?></td>
		<td style="padding: 5px;"><?=$item['line']?></td>
		<td style="padding: 5px;"><?=$item['class']?></td>
		<td style="padding: 5px;"><?=$item['function']?></td>
	</tr>
<?endforeach?>
</table>