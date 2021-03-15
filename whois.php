<?php
include 'phpWhoisClass/src/whois.main.php';
$whois = new Whois();
$query = 'seznam.cz';
$result = $whois->Lookup($query);
function need_data($result,$tmp){
    foreach ($result as $item) {
        if(key($result)==="registered" || key($result)==="admin"|| key($result)==="domain"|| key($result)==="owner" )
        {
            if(is_array($item)) {
                unset($item["keyset"]);
                unset($item["handle"]);
                unset($item["registrar"]);
                unset($item["nsset"]);
                unset($item["nserver"]);
                foreach ($item as $v) {
                    if(key($item)==="address")
                    {
                        $item["address"]=implode(" ", $item["address"]);
                    }
                    if(key($item)==="status")
                    {
                        $item["status"]=implode(",", $item["status"]);
                    }
                    next($item);
                }
            }
            $tmp[key($result)]=$item;
            next($result);
            continue;
        }
        next($result);
        if(is_array($item))
        {
            $tmp=need_data($item,$tmp);
        }

    }
return $tmp;
}
$out=need_data($result,array());
$fileName="tests.txt";
$txt = print_r($out, true);
file_put_contents($fileName, $txt);