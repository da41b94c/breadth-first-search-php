# Пример алгоритма «Поиск в ширину»

Поиск пути между станциями метро. 
В php граф можно представить в виде массива. 
Ключ - это уникальное название станции метро. 
Значение - это список станций, куда можно отправиться с текущей станции (ключа). 
Пересадочные станции - это разные станции. Отличие от обычных только в том, что они расположены близко друг к другу.

<pre>
$metro = new metro();

var_dump( $metro->isPathExists( 'Купчино', 'Автово' ) );
var_dump( $metro->getPath( 'Купчино', 'Автово' ) );

var_dump( $metro->isPathExists( 'Купчино', 'Станция метро 3/4' ) );
var_dump( $metro->getPath( 'Купчино', 'Станция метро 3/4' ) );
</pre>

https://developer.donnoval.ru/breadth-first-search-php/
