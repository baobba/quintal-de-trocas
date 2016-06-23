<?php
    foreach ($toys->result() as $toy) {
        echo '
        <li>
            <div class="box ie-fix">
		    ';

        echo sprintf('
            <div class="img">
                <div class="tbl-cell"><a href="%s"><img src="%s" alt="%s" width="225"></a></div>
            </div>
        ', $base_url . URL_PRODUTOS_DETALHE . $toy->id, $base_url . URL_UPLOAD_IMAGE . $toy->image, $toy->name);

        echo sprintf('<strong class="name"><a href="%s">%s</a></strong>', $base_url . URL_PRODUTOS_DETALHE . $toy->id, strlen($toy->name) > 25 ? substr($toy->name, 0, 23) . "..." : $toy->name);
        echo sprintf('<a href="%s"><p>%s</p></a>', $base_url . URL_PRODUTOS_DETALHE . $toy->id, truncate($toy->description, 70));
        echo sprintf('<a href="%s" class="btn">Quero trocar</a>', $base_url . URL_PRODUTOS_DETALHE . $toy->id);

        echo '</div>';
        echo '</li>';
    }
    
    if ($nextLink) {
        echo sprintf('<a href="%s" class="next"></a>', $nextLink);
    }    
?>

