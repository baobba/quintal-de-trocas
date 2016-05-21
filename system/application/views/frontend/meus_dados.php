<div class="page-title page-title3">
    <div class="in">
        <h1>Meus dados</h1>
        <ul class="breadcrumbs">
            <li><a href="<?php echo $base_url; ?>">Home</a></li>
            <li>Meus dados</li>
        </ul>
    </div>
</div>
<!-- /page-title -->

<?php
if ($tabSelected !== null) {
    echo sprintf("<script>$(function(){ $('#tabs-1').tabs({selected:%s});});</script>", $tabSelected);
}
?>

<article class="user-area" id="tabs-1">
    <aside>
        <div class="avatar">
            <div class="img"><img alt="<?php echo trim($formxUserData->use_field('name')->get_posted()); ?>" src="<?php echo $base_url . URL_UPLOAD_IMAGE . $user['avatar']; ?>"></div>
            <strong class="name"><?php echo trim(strtok($formxUserData->use_field('name')->get_posted(), ' ')); ?></strong>
        </div>
        <ul class="side-menu">
            <li><a href="#tab-1-1">Meus Dados</a></li>
            <li><a href="#tab-1-2">Meus Brinquedos</a></li>
            <?php if (count($toysExchanged->result()) > 0) { ?>
                <li><a href="#tab-1-3">Brinquedos trocados</a></li>
            <?php } ?>
            <li><a href="#tab-1-4">Adicionar Brinquedo</a></li>
            <li><a href="#tab-1-5">Minhas Trocas</a></li>
        </ul>
    </aside>
    <div class="column">
        <div class="block ie-fix">
            <div id="tab-1-1">
                <div class="personal-data">
                    <?php
                    if (count($formxUserData->get_form_errors())) {
                        foreach ($formxUserData->get_form_errors() as $error) {
                            echo '<p style="color:#990000">' . $error . '</p>';
                        }
                    }

                    echo $formxUserDataView;
                    ?>
                </div>
            </div>
            <div id="tab-1-2">
                <div class="toys">
                    <a href="<?php echo $base_url . URL_USUARIO_MEUS_DADOS_CRIAR_BRINQUEDO; ?>" class="btn-new">Cadastrar novo brinquedo</a>
                    <div class="separator">
                        <ul class="toys-list">

                            <?php
                            $t = 0;
                            foreach ($toys->result() as $toy) {
                                $t++;
                                echo sprintf('
                                        <li class="ie-fix">
								            <div class="img">
									           <span class="tbl-cell">
									               <img src="%s" alt="%s" width="225">
								                </span>
								            </div>
								            <!-- <strong class="count ie-fix">1</strong>--> 
								            <strong class="name">
                                                <a href="#">%s</a>
                                            </strong>
							                <a href="%s" class="btn-edit">Editar</a>
								            <a href="%s" class="btn-delete" onclick="return confirm(\'Tem certeza que deseja excluir?\')">Excluír</a>
							             </li>', $base_url . URL_UPLOAD_IMAGE . $toy->image, $toy->name, $toy->name, $base_url . URL_USUARIO_EDITAR_BRINQUEDO . $toy->id, $base_url . URL_USUARIO_DELETAR_BRINQUEDO . $toy->id);
                            }
                            ?>
                        </ul>
                    </div>
                            <?php if ($t >= 6) { ?>
                        <div class="separator">
                            <a href="<?php echo $base_url . URL_USUARIO_MEUS_DADOS_CRIAR_BRINQUEDO ?>" class="btn-new">Cadastrar novo brinquedo</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
<?php if (count($toysExchanged->result()) > 0) { ?>
                <div id="tab-1-3">
                    <div class="toys">
                        <div class="separator">
                            <ul class="toys-list">
                <?php
                $t = 0;
                foreach ($toysExchanged->result() as $toy) {
                    $t++;
                    echo sprintf('
                                            <li class="ie-fix">
                                                                                <div class="img">
                                                                                       <span class="tbl-cell">
                                                                                           <img src="%s" alt="%s" width="225">
                                                                                    </span>
                                                                                </div>
                                                                                <!-- <strong class="count ie-fix">1</strong>--> 
                                                                                <strong class="name">
                                                    <a href="#">%s</a>
                                                </strong>
                                                                            
                                                                         </li>', $base_url . URL_UPLOAD_IMAGE . $toy->image, $toy->name, $toy->name, $base_url . URL_USUARIO_EDITAR_BRINQUEDO . $toy->id, $base_url . URL_USUARIO_DELETAR_BRINQUEDO . $toy->id);
                }
                ?>
                            </ul>
                        </div>
                                <?php if ($t >= 6) { ?>
                            <div class="separator">
                                <a href="<?php echo $base_url . URL_USUARIO_MEUS_DADOS_CRIAR_BRINQUEDO ?>" class="btn-new">Cadastrar novo brinquedo</a>
                            </div>
                                <?php } ?>
                    </div>
                </div>
                            <?php } ?>
            <div id="tab-1-4">
                <div class="toy-info">
                    <?php
                    if (count($formxNewToy->get_form_errors())) {
                        foreach ($formxNewToy->get_form_errors() as $error) {
                            echo '<p style="color:#990000">' . $error . '</p>';
                        }
                    } elseif ($formxNewToy->is_sucess()) {
                        echo '
                                <h2 style="color:#A2D246">
                                    Brinquedo cadastrado.
                                </h2>
            			    
                                <div class="separator">
                                    <a href="' . $base_url . URL_USUARIO_MEUS_DADOS . '2" class="btn-new">Cadastrar novo brinquedo</a>
                                </div>';
                    }

                    if ($formxNewToy->is_sucess() === false) {
                        echo $formxNewToyView;
                    }
                    ?>
                </div>
            </div>
            <div id="tab-1-5">

                <div class="toys-exchange">
                    <h2>Pedidos de troca</h2>
                    <ul class="exchange-list">
                    <?php
                    $reputations = array();

                    foreach ($exchanges->result() as $exchange) {
                        $myExchange = $exchange->my_exchange == 1;

                        $exchangeType = $exchange->type == CmsExchange::TYPE_PONTO_CORREIOS ? 'Correios' : 'Ponto de Troca';

                        $message = '';

                        if ($myExchange == false && $exchange->accepted == CmsExchange::ACCEPTED_NOT_YET && $exchange->finalized == CmsExchange::FINALIZED_NO) {
                            $message.= '<p><i class="fa fa-truck"></i> Aguardando resposta</p>';
                            $message.= sprintf('<a href="%s" class="btn-accept">Aceitar</a>', $base_url . URL_USUARIO_ACEITAR_TROCA . $exchange->id);
                            $message.= sprintf('<a href="%s" class="btn-delete">Recusar</a>', $base_url . URL_USUARIO_RECUSAR_TROCA . $exchange->id);
                        } elseif ($myExchange && $exchange->accepted == CmsExchange::ACCEPTED_NOT_YET && $exchange->finalized == CmsExchange::FINALIZED_NO) {
                            $message.= '<p><i class="fa fa-truck"></i> Aguardando resposta</p>';
                        }

                        if ((($myExchange && $exchange->from_rating !== null) ||
                                (!$myExchange && $exchange->to_rating !== null)) && $exchange->finalized == CmsExchange::FINALIZED_NO) {
                            $message.= '<p><i class="fa fa-truck"></i> Você finalizou a troca.<br /> Aguarde outro usuário finalizar também.</p>';
                        } elseif ($exchange->accepted == CmsExchange::ACCEPTED_YES && $exchange->finalized == CmsExchange::FINALIZED_NO) {

                            $message.= '<p><i class="fa fa-truck"></i> Troca em andamento</p>';
                            $message.= '<ul>';

                            if ($exchange->type == CmsExchange::TYPE_PONTO_CORREIOS) {
                                $message.= '<li><strong>Troca por:</strong> Correios</li>';

//                                         if ($myExchange) {
//                                             $message.= sprintf('<li><strong>Endereço:</strong> %s, %s - %s</li>', $exchange->to_client_address, $exchange->to_client_address_no, $exchange->to_client_neighborhood);
//                                             if (trim($exchange->to_client_complement) !== '') {
//                                                 $message.= sprintf('<li><strong>Complemento:</strong> %s</li>', $exchange->to_client_complement);
//                                             }
//                                             $message.= sprintf('<li><strong>CEP:</strong> %s</li>', $exchange->to_client_zip_code);
//                                             $message.= sprintf('<li><strong>Cidade e Estado:</strong> %s - %s</li>', $exchange->to_client_city, $exchange->to_client_state);
//                                         } else {
//                                             $message.= sprintf('<li><strong>Endereço:</strong> %s, %s - %s</li>', $exchange->from_client_address, $exchange->from_client_address_no, $exchange->from_client_neighborhood);
//                                             if (trim($exchange->from_client_complement) !== '') {
//                                                 $message.= sprintf('<li><strong>Complemento:</strong> %s</li>', $exchange->from_client_complement);
//                                             }
//                                             $message.= sprintf('<li><strong>CEP:</strong> %s</li>', $exchange->from_client_zip_code);
//                                             $message.= sprintf('<li><strong>Cidade e Estado:</strong> %s - %s</li>', $exchange->from_client_city, $exchange->from_client_state);
//                                         }
//                                     } else {
//                                         $message.= '<li><strong>Troca por:</strong> Ponto de trocas.</li>';
//                                         $message.= '<li>(Combine com a outra pessoa por meio de mensagens.)</li>';
//                                         $message.= sprintf('<li><a href="%s">Ver pontos de trocas</a></li>', $base_url . URL_PONTOS_DE_TROCAS);
                            }

                            $message.= '</ul>';

                            if (($myExchange && $exchange->from_rating == null) |
                                    (!$myExchange && $exchange->to_rating == null)) {
                                $message.= sprintf('<form action="%s" method="post">', $base_url . URL_USUARIO_FINALIZAR_TROCA . $exchange->id);
                                $message.= '<br />';
                                $message.= '<input name="star" value="1" type="radio" class="star"/>';
                                $message.= '<input name="star" value="2" type="radio" class="star"/>';
                                $message.= '<input name="star" value="3" type="radio" class="star"/>';
                                $message.= '<input name="star" value="4" type="radio" class="star"/>';
                                $message.= '<input name="star" value="5" type="radio" class="star"/>';
                                $message.= '<br />';
                                $message.= '<input class="btn-done" type="submit" value="Finalizar Troca" />';
                                $message.= '<span data-tooltip="Avalie esta troca"><i class="fa fa-info-circle"></i></span>';
                                $message.= '</form>';
                            }
                        } elseif ($exchange->accepted == CmsExchange::ACCEPTED_NO && $exchange->finalized == CmsExchange::FINALIZED_NO) {
                            $message.= '<p><i class="fa fa-truck"></i> Troca não aceita</p>';
                        } elseif ($exchange->finalized == CmsExchange::FINALIZED_YES) {
                            $message.= '<p><i class="fa fa-truck"></i> Troca finalizada</p>';
                        }

                        echo '<li>';

                        # info

                        $name = $myExchange ? $exchange->to_client_name : $exchange->from_client_name;
                        $firstName = strtok($name, ' ');
                        $city = $myExchange ? $exchange->to_client_city : $exchange->from_client_city;
                        $state = $myExchange ? $exchange->to_client_state : $exchange->from_client_state;
                        $state = strtoupper($state);

                        $related = $myExchange ? CmsToy::getRelated($exchange->to_client_id, $exchange->to_toy_id, 5) : CmsToy::getRelated($exchange->from_client_id, $exchange->from_toy_id, 5);
                        $related = $related->result();


                        echo '<div class="exchange-wrapper grid-100">';
                        echo '<div class="person-info grid-50">';

                        if ($myExchange) {
                            $reputation = CmsClient::getReputationByUserId($exchange->to_client_id);
                        } else {
                            $reputation = CmsClient::getReputationByUserId($exchange->from_client_id);
                        }

                        $s = 'fa-frown-o';

                        if ($reputation >= 50) {
                            $s = 'fa-smile-o';
                        }

                        $reputation = sprintf('<span data-tooltip="Reputação"><i class="fa %s">%s%%</i> </span>', $s, $reputation);

                        echo sprintf('<h2 class="person-name">%s %s</h2>', $name, $reputation);
                        echo sprintf('<p class="person-tagline">%s, %s</p>', $city, $state);

                        echo '<div class="products-exchange-info">';
                        echo sprintf('<p>%s gostaria de trocar por %s.</p>', $firstName, $exchangeType);

                        echo $message;

                        echo '</div>';
                        echo '</div>';

                        echo '<div class="products-info grid-50">';
                        $imageUrl = $base_url . URL_UPLOAD_IMAGE . ($myExchange ? $exchange->from_toy_image : $exchange->to_toy_image);
                        $productName = $myExchange ? $exchange->from_toy_name : $exchange->to_toy_name;
                        $productUrl = $base_url . URL_PRODUTOS_DETALHE . ($myExchange ? $exchange->from_toy_id : $exchange->to_toy_id);

                        echo '<div class="grid-50">';
                        echo sprintf('<img alt="%s" src="%s" style="width:156px">', $productName, $imageUrl);
                        echo '<strong class="product-title">Meu Produto:</strong>';
                        echo sprintf('<h4 class="product-name"><a href="%s">%s</a></h4>', $productUrl, $productName);
                        echo '</div>';

                        $imageUrl = $base_url . URL_UPLOAD_IMAGE . (!$myExchange ? $exchange->from_toy_image : $exchange->to_toy_image);
                        $productName = !$myExchange ? $exchange->from_toy_name : $exchange->to_toy_name;
                        $productUrl = $base_url . URL_PRODUTOS_DETALHE . (!$myExchange ? $exchange->from_toy_id : $exchange->to_toy_id);

                        echo '<div class="grid-50">';
                        echo sprintf('<img alt="%s" src="%s" style="width:156px">', $productName, $imageUrl);
                        echo '<strong class="product-title">Está interessado em:</strong>';
                        echo sprintf('<h4 class="product-name"><a href="%s">%s</a></h4>', $productUrl, $productName);
                        echo '</div>';

                        echo '</div>';

                        echo '</div>';

                        if (count($related)) {
                            # products
                            echo '<div class="related-products">';
                            echo sprintf('<h2 class="related-products-title">Veja os outros itens de %s</h2>', $firstName);

                            echo '<ul class="related-products-list grid-100">';
                            foreach ($related as $product) {
                                echo '<li class="grid-25">';
                                echo sprintf('<img alt="%s" src="%s" style="width:85px">', $product->name, $base_url . URL_UPLOAD_IMAGE . $product->image);
                                echo sprintf('<h5 class="related-product-name"><a href="%s">%s</a></h5>', $base_url . URL_PRODUTOS_DETALHE . $product->id, $product->name);
                                echo '</li>';
                            }

                            echo '</ul>';

                            echo '</div>';
                        }

                        $messages = CmsExchangeMessage::getMessages($exchange->id);

                        $header = array_shift($messages);
                        $header = (array) $header;

                        $firstName = strtok($header['client_message_name'], ' ');

                        # messages
                        echo '<div class="messages">';
                        echo '<div class="item">';
                        echo '<div class="msg-head group">';
                        echo sprintf('<span class="messages-count">%d</span>', count($messages));
                        echo '<h2 class="messages-title">Mensagem</h2>';
                        echo sprintf('<div class="photo"><img alt="%s" src="%s" style="width:77px"></div>', $firstName, $base_url . URL_UPLOAD_IMAGE . $header['client_message_avatar']);
                        echo '<div class="descr group">';
                        echo sprintf('<div class="str">%s <em class="date">(%s)</em></div>', $firstName, date_create_from_format('Y-m-d H:i:s', $header['created_at'])->format('d/m/Y H\hm'));
                        echo sprintf('<span class="subject">Interesse no brinquedo <a href="%s">%s</a></span>', $base_url . URL_PRODUTOS_DETALHE . $header['exchange_to_toy_id'], $header['exchange_to_toy_name']);
                        #echo '<a class="btn-reply" href="#">Responder</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="entity">';
                        echo sprintf('<p>%s</p>', $header['message']);

                        #$last = end($anwser);
                        #$last = is_object($last) ? $last->id : null;

                        $i = 0;
                        foreach ($messages as $anwser) {
                            $row = $i % 2 === 0 ? 'arrow-author' : '';
                            echo '<div class="answer">';
                            echo sprintf('<span class="arrow  %s">&nbsp;</span>', $row);
                            echo sprintf('<div class="photo"><img alt="%s" src="%s" style="width:52px"></div>', strtok($anwser->client_message_name, ' '), $base_url . URL_UPLOAD_IMAGE . $anwser->client_message_avatar);
                            echo sprintf('<div class="descr">%s</div>', $anwser->message);
                            echo '</div>';
                            $i++;
                        }

                        if ($header['finalized'] == CmsExchange::FINALIZED_NO) {
                            echo '<div class="answer">';
                            echo '<span class="arrow">&nbsp;</span>';
                            echo sprintf('<form action="%s" method="post">', $base_url . URL_USUARIO_MEUS_DADOS_MENSAGENS);
                            echo sprintf('<input type="hidden" value="%s" name="e" />', $header['exchange_id']);
                            echo sprintf('<textarea name="m" id="m%s"></textarea>', $header['exchange_id']);
                            echo '<input type="submit" value="Responder" />';
                            echo '</form>';
                            echo '</div>';
                        }

                        echo '</div>';

                        echo '</div>';
                        echo '</div>';

                        echo '</li>';
                    }
                    ?>
                    </ul>

                </div>
            </div>
            <div id="tab-1-5">
                <div class="messages" id="accordion-2">
                        <?php
//                         foreach($messages as $message) {
//                             $header = array_shift($message);
//                             echo '<div class="item ie-fix">';
//                                 echo '<div class="msg-head">';
//                                     echo '<div class="photo">';
//                                         echo sprintf('<img src="%s" alt="%s" width="77">', $base_url . URL_UPLOAD_IMAGE . $header['client_message_avatar'], $header['client_message_name']);
//                                     echo '</div>';
//                                     echo '<div class="descr">';  
//                                         echo sprintf('<div class="str">Mensagem enviada para %s <em class="date">(%s)</em></div>', $header['to_client_id'] == $user['id'] ? 'voc&ecirc;' : $header['to_client_name'], date_create_from_format('Y-m-d H:i:s', $header['created_at'])->format('d/m/Y H\hm'));
//                                         echo sprintf('<span class="subject">Interesse no brinquedo <a href="%s">%s</a></span>', $base_url . URL_PRODUTOS_DETALHE . $header['exchange_to_toy_id'], $header['exchange_to_toy_name']);
//                                         echo sprintf('<span class="subject">Brinquedo de Troca <a href="%s">%s</a></span>', $base_url . URL_PRODUTOS_DETALHE . $header['exchange_from_toy_id'], $header['exchange_from_toy_name']);
//                                         echo sprintf('<span class="subject">Troca por: %s</span>', $header['type'] == CmsExchange::TYPE_PONTO_CORREIOS ? 'Correios' : 'Ponto de Troca');
//                                     echo '</div>';
//                                 echo '</div>';
//                                 echo '<div class="entity">';
//                                     echo sprintf('<p>%s</p>', $header['message']);
//                                     foreach ($message as $_message) {
//                                         echo '<div class="answer ie-fix">';
//                                             echo '<span class="arrow">&nbsp;</span>';
//                                             echo '<div class="photo">';
//                                                 echo sprintf('<img src="%s" alt="%s", width="52">', $base_url . URL_UPLOAD_IMAGE . $_message['client_message_avatar'], $_message['client_message_name']);
//                                             echo '</div>';
//                                             echo '<div class="descr">';
//                                                 echo sprintf('<p>%s</p>', $_message['message']);
//                                             echo '</div>';
//                                         echo '</div>';
//                                     }
//                                     if ($header['finalized'] == CmsExchange::FINALIZED_NO) {
//                                         echo '<div class="answer ie-fix">';
//                                             echo '<span class="arrow">&nbsp;</span>';
//                                             echo sprintf('<form action="%s" method="post">', $base_url . URL_USUARIO_MEUS_DADOS_MENSAGENS);
//                                                 echo sprintf('<input type="hidden" value="%s" name="e" />', $header['exchange_id']);
//                                                 echo sprintf('<textarea name="m" id="m%s"></textarea>', $header['exchange_id']);
//                                                 echo '<input type="submit" value="Responder" />';
//                                                 echo '</p>';
//                                             echo '</form>';   
//                                         echo '</div>';
//                                     }
//                                 echo '</div>';
//                             echo '</div>';
//                         }
                        ?>
                </div>
            </div>
        </div>
    </div>
</article>
<!-- /user-area -->