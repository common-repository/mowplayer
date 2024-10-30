<?php
    //include('../../../../wp-load.php');
    //include('../../../../wp-admin/includes/file.php');

    /**
     * Custom ads.txt
     */
     /*
    $path = get_home_path();
    //$ads_file = fopen($path."ads.txt", "r");

    $ads_file = $path . 'ads.txt';



    if(file_exists($ads_file)){
        $prev_content = file_get_contents($ads_file);

        $default_content = "\n#Mowplayer\ngoogle.com, pub-6864402317197092, RESELLER, f08c47fec0942fa0\nadvertising.com,12010, RESELLER\ntritondigital.com, 38633, DIRECT, 19b4454d0b87b58b\nbeachfront.com, 1626, RESELLER, e2541279e8e2ca4d\nsmartadserver.com, 3261, RESELLER\ncontextweb.com, 560288, RESELLER, 89ff185a4c4e857c\npubmatic.com, 156439, RESELLER, 5d62403b186f2ace\npubmatic.com, 154037, RESELLER, 5d62403b186f2ace\nrubiconproject.com, 16114, RESELLER, 0bfd66d529a5\nappnexus.com, 3703, RESELLER, f5ab79cb980f11d1\ndistrictm.io, 101760, RESELLER, 3fd707be9c4527c3\nloopme.com, 5679, RESELLER, 6c8d5f95897a5a3b\nxad.com, 958, RESELLER, 81cbf0a75a5e0e9a\nrhythmone.com, 2564526802, RESELLER, a670c89d4a324e47\nsmaato.com, 1100044045, RESELLER, 07bcf65f187117b4\npubnative.net, 1006576, RESELLER, d641df8625486a7b\nadyoulike.com, b4bf4fdd9b0b915f746f6747ff432bde, RESELLER\naxonix.com, 57264, RESELLER\nadmanmedia.com, 43, RESELLER\nappnexus.com,1408,DIRECT,f5ab79cb980f11d1\nkonektimedia.com, 304-b4437, RESELLER\ngoogle.com, pub-7612738114777168, RESELLER, f08c47fec0942fa0\ngoogle.com, pub-1290995901905588, RESELLER, f08c47fec0942fa0\nprodooh.com, pdh-1534, DIRECT";

        //$adstxtfile = file_put_contents($ads_file, $prev_content.$default_content);
        //return $adstxtfile;

        $deleted = unlink($ads_file);

        $myfile = fopen($path."ads.txt", "w") or die("Unable to open file!");
        $txt = $prev_content;
        fwrite($myfile, $txt);
        $txt = $default_content;
        fwrite($myfile, $txt);
        fclose($myfile);
        chmod($myfile, 0777);


    } else {
        $myfile = fopen($path."ads.txt", "w") or die("Unable to open file!");
        $txt = "\n#Mowplayer\ngoogle.com, pub-6864402317197092, RESELLER, f08c47fec0942fa0\nadvertising.com,12010, RESELLER\ntritondigital.com, 38633, DIRECT, 19b4454d0b87b58b\nbeachfront.com, 1626, RESELLER, e2541279e8e2ca4d\nsmartadserver.com, 3261, RESELLER\ncontextweb.com, 560288, RESELLER, 89ff185a4c4e857c\npubmatic.com, 156439, RESELLER, 5d62403b186f2ace\npubmatic.com, 154037, RESELLER, 5d62403b186f2ace\nrubiconproject.com, 16114, RESELLER, 0bfd66d529a5\nappnexus.com, 3703, RESELLER, f5ab79cb980f11d1\ndistrictm.io, 101760, RESELLER, 3fd707be9c4527c3\nloopme.com, 5679, RESELLER, 6c8d5f95897a5a3b\nxad.com, 958, RESELLER, 81cbf0a75a5e0e9a\nrhythmone.com, 2564526802, RESELLER, a670c89d4a324e47\nsmaato.com, 1100044045, RESELLER, 07bcf65f187117b4\npubnative.net, 1006576, RESELLER, d641df8625486a7b\nadyoulike.com, b4bf4fdd9b0b915f746f6747ff432bde, RESELLER\naxonix.com, 57264, RESELLER\nadmanmedia.com, 43, RESELLER\nappnexus.com,1408,DIRECT,f5ab79cb980f11d1\nkonektimedia.com, 304-b4437, RESELLER\ngoogle.com, pub-7612738114777168, RESELLER, f08c47fec0942fa0\ngoogle.com, pub-1290995901905588, RESELLER, f08c47fec0942fa0\nprodooh.com, pdh-1534, DIRECT";
        fwrite($myfile, $txt);
        fclose($myfile);
        chmod($myfile, 0777);
        //$default_content = "adstxtcustommow \ncustomads \nmowads";
        //$adstxtfile = file_put_contents($ads_file, $default_content);
        //return $adstxtfile;
    }

*/
 ?>
