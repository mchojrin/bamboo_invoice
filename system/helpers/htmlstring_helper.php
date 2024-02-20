<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// function is self explanatory
function convertToHtmlSpecialChars($phrase)
{
    $from = array("‘"     ,"’"    ,"‚"       ,'“'      ,'”'      ,'„'       ,"\'"   ,"\""    ,"'"    ,'"'     ,'['    ,']'    ,'{'     ,'}'     ,'('    , ')'    ,"\n"   ,"\r","\t"    ,"\r\n");
    $to =   array("&#39;","&#39;","&sbquo;","&ldquo;","&rdquo;","&bdquo;","&#39;","&quot;","&#39;","&quot;",'&#91;','&#93;','&#123;','&#125;','&#40;', '&#41;','<br/>',''  ,'&nbsp;','<br/>');
    $ret = str_replace($from, $to, $phrase);
    return $ret;
}
?>
