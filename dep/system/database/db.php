<?php
/**
*	Manages and provides db services
*/
class DataBaseManager extends Config
{  
	/**
	* DB Connected???
	*/
	var $DB_CONNECTED = 0;

	function db_connect($db="")
	{
		if ($this->DB_CONNECTED != 1)
		{
			if ($db == "")
			{
				$dbh = mysql_pconnect($this->host, $this->user, $this->password) or die("Unable to connect to SQL server: ". mysql_error());
				mysql_select_db($this->db_name,$dbh) or die("Unable to select DB: ".mysql_error());
				$this->DB_CONNECTED = 1;
				return $dbh;
			}
			else
			{
				$dbh = mysql_pconnect($this->host, $this->user, $this->password) or die("Unable to connect to SQL server: ". mysql_error());
				mysql_select_db($this->db_default_name,$dbh) or die("Unable to select DB: ".mysql_error());
				$this->DB_CONNECTED = 1;
				return $dbh;
			}
            mysql_close($dbh);
		}
	}
	function get_insert_string ( $record )
	{
		// Takes an associative array of $record and makes a string of fields and values like
		// (field1, field2, field3) VALUES ('value1', 'value2', 'value3')
        $string["fields"] = $string["values"] = "";
		foreach ( $record as $key=>$val )
		{
			//if ($key && $val)
			//{
				$string["fields"] .= "$key, ";
				if ((preg_match('/PASSWORD/i', $val)) || (preg_match('/CURRENT_TIMESTAMP/i', $val)) || (preg_match('/CURDATE/i', $val)) || (preg_match('/CURTIME/i', $val)) || (preg_match('/NOW/i', $val)) )
				{
					$string["values"] .= $val. ", ";
				}
				else $string["values"] .= "'" . addslashes($val) . "', ";
			//}
		}
		// Remove the last comma...
		$string["fields"]=ereg_replace(", $","",$string["fields"]);
		$string["values"]=ereg_replace(", $","",$string["values"]);
		// Return the completed string.
		return " ( $string[fields] ) VALUES ( $string[values] );";
	}
	function get_insert_string_fields( $record )
	{
		// Takes an associative array of $record and makes a string of fields and values like
		// (field1, field2, field3) VALUES ('value1', 'value2', 'value3')
		foreach ( $record as $key=>$val )
		{
			$string["fields"] .= "$key, ";
		}
		$string["fields"]=ereg_replace(", $","",$string["fields"]);
		return " ( $string[fields] ) VALUES ";
	}
	function get_insert_string_multiple ( $record )
	{
		// Takes an associative array of $record and makes a string of fields and values like
		// (field1, field2, field3) VALUES ('value1', 'value2', 'value3')
		foreach ( $record as $key=>$val )
		{
			//if ($key && $val)
			//{
				$string["fields"] .= "$key, ";
				if (preg_match('/PASSWORD/i', $val)) $string["values"] .= $val. ", ";
				else $string["values"] .= "'" . addslashes($val) . "', ";
			//}
		}
		// Remove the last comma...
		$string["values"]=ereg_replace(", $","",$string["values"]);
		// Return the completed string.
		return " ( $string[values] ),";
	}
	function get_update_string ($record)
	{
		// Similar to above, but instead makes a string of
		foreach ( $record as $key=>$val )
		{
			if ($record[$key])
			{
				$strings .= $key . " = '" . addslashes($val) . "', ";
			}
		}
		$strings = ereg_replace(", $", " ", $strings);
		return $strings;
	}
	function db_query ($statement, $db="")
	{
		if ($this->DB_CONNECTED != 1)
		{
			if ($db == "") $this->db_connect();
			else $this->db_connect(1);
		}
		$result = mysql_query($statement) or die("<pre>\n\nCan't perform query: ".mysql_error()."\n\n".$this->readable_sql($statement)."\n\n</pre>");
		$num_rows = $this->affected_rows();
		return array($result, $num_rows);
	}
	function get_link()
	{
		return $this->db_connect();
	}
	function db_num_rows ($result)
	{
		return @mysql_numrows($result);
	}
	function db_result ($result,$i=-1)
	{
		if ($i >= 0)
		{
			@mysql_data_seek($result,$i);
		}
		return @mysql_fetch_array($result);
	}
	function db_last_id()
	{
		return mysql_insert_id();
	}
	function free_result($result)
	{
		return mysql_free_result($result);
	}
	function affected_rows()
	{
		return  mysql_affected_rows();
	}
	/* Print readable sql statement */
	function readable_sql($statement)
	{
		$statement = eregi_replace(" SELECT "   ,"\n SELECT ", $statement);
		$statement = eregi_replace(" DISTINCT " ,"\t DISTINCT ", $statement);
		$statement = eregi_replace(" UNIQUE "   ,"\t UNIQUE ", $statement);
		$statement = eregi_replace(" FROM "     ,"\n FROM ", $statement);
		$statement = eregi_replace(" WHERE "    ,"\n WHERE ", $statement);
		$statement = eregi_replace(" AND "      ,"\n AND ", $statement);
		$statement = eregi_replace(" OR "       ,"\t OR ", $statement);
		$statement = eregi_replace(" GROUP BY " ,"\n GROUP BY ", $statement);
		$statement = eregi_replace(" ORDER BY " ,"\n ORDER BY ", $statement);
		return $statement;
	}
	function get_select_options($mySqlQuery, $myArrayIndexName, $myArrayValues)
	{
		list($qh, $num) = $this->db_query($mySqlQuery);
		$data = $this->db_result($qh);
		$myRet = Array();
		for ($i = 0; $i < $num; $i++)
		{
			$myRet[trim($data[$myArrayIndexName])] = $data[$myArrayValues];
			$data = $this->db_result($qh);
		}
		$this->free_result($qh);
		return $myRet;
	}
}
?>