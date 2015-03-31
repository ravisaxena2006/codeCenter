<?php
header( "Content-Type: text/csv;charset=utf-8" );
header('Content-Disposition: attachment; filename=Manufacturer_'.time().'.csv');
header('Pragma: no-cache');
readfile("amazondata.csv");	
