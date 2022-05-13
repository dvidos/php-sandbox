<?php
echo '/* Export from server ' . $_SERVER['SERVER_NAME'] . ', on ' . date('Y-m-d H:i:s') . ' */' . PHP_EOL;
echo PHP_EOL;
echo PHP_EOL;


foreach ($tables_rows as $table_name => $table_rows)
{
	echo '/* table ' . $table_name . ', ' . count($table_rows) . ' rows */' . PHP_EOL;
	echo PHP_EOL;
	echo cms()->db->show_create_table($table_name) . PHP_EOL;
	echo PHP_EOL;
	
	
	foreach ($table_rows as $row)
	{
		$fields = [];
		$values = [];
		foreach ($row as $name => $value)
		{
			$fields[] = '`' . $name . '`';
			if (is_numeric($value))
				$values[] = $value;
			else if (is_string($value))
				$values[] = cms()->db->escape_string($value);
		}
		
		$line = 'INSERT INTO ' . $table_name . ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ');';
		echo $line . PHP_EOL;
		if (strpos($line, '\\n') !== false)
			echo PHP_EOL;
	}
	
	echo PHP_EOL;
	echo PHP_EOL;
}

echo PHP_EOL;
echo PHP_EOL;

