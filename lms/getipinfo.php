<?php
  $title=$titlepage="";
  $ip = $_GET["ip"];

if ($ip!="")
{
        $sock=fsockopen ("whois.ripe.net",43,$errno,$errstr);
        if (!$sock)
        {
                echo ($errstr($errno)."<br>");
        }
        else
        {
                fputs ($sock,$ip."\r\n");
                while (!feof($sock))
                {
                        echo (str_replace(":",":&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",fgets ($sock,128))."<br>");
                }
        }
        fclose ($sock);
}

  
?>