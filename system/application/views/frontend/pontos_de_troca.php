</div><!-- class 'w1' -->
<div class="page-title">
    <div class="in">
        <h2>Pontos de troca | </h2><span>compartilhando mais</span>
    </div>
</div>
<div class="w1">
    <article class="exchange-area">
        <div class="decor">
            <div class="block ie-fix">
                <div class="cols">
                    <div class="col-l">
                        <div class="col-in">
                            <div class="choose-form">
                                <form action="<?php echo $base_url . URL_PONTOS_DE_TROCAS ?>" method="post">
                                    <fieldset>
                                        <label for="lbl-01">Selecione seu estado</label>
                                        <div class="sel-hold">
                                            <select id="lbl-01" class="cs-3" title="Selecione" name="state"
                                                    onchange="this.form.submit()">
                                                <option value="">Selecione seu estado</option>
                                                <?php
                                                foreach ($states as $state) {
                                                    $selected = $state == $_state ? 'selected="selected"' : '';
                                                    echo sprintf('<option value="%s" %s>%s</option>', $state, $selected, $state);
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <label for="lbl-02">Selecione sua cidade</label>
                                        <div class="sel-hold">
                                            <select id="lbl-02" class="cs-3" title="Selecione" name="city"
                                                    onchange="this.form.submit()">
                                                <option value="">Selecione sua cidade</option>
                                                <?php
                                                foreach ($cities as $city) {
                                                    $selected = $city == $_city ? 'selected="selected"' : '';
                                                    echo sprintf('<option value="%s" %s>%s</option>', $city, $selected, $city);
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-r">
                        <div class="col-in">
                            <h3>Lista dos atuais pontos de troca</h3>
                            <ul class="contact-list">
                                <?php
                                foreach ($exchangePoints as $exchangePoint) {
                                    echo '<li><div class="in">';
                                    echo sprintf('<strong class="name">%s</strong>', $exchangePoint->name);
                                    echo sprintf('<strong class="name">%s . %s %s - %s (%s/%s)</strong>', $exchangePoint->address, $exchangePoint->address_no, $exchangePoint->complement, $exchangePoint->neighborhood, $exchangePoint->city, $exchangePoint->state);
                                    echo sprintf('<strong class="str">%s</strong>', $exchangePoint->phone);
                                    echo '</div></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /block -->
            </div>
            <h1>Perguntas Frequentes</h1>
            <div class="block ie-fix">
                <div class="cols">
                    <div class="col-in">
                        <h2>
                            O que &eacute; um Ponto de Trocas?
                        </h2>
                        <p>
                            &Eacute; um estabelecimento parceiro do Quintal de Trocas que atua como uma refer&ecirc;ncia
                            f&iacute;sica
                            para os participantes do portal. O Ponto de Trocas compartilha dos mesmos valores do
                            Quintal:
                            sustentabilidade, consumo consciente e respeito a sociedade.
                        </p>
                        <p>
                            O Quintal de Trocas &eacute; 100% gratuito e crian&ccedil;as de&nbsp;<strong>todas as
                                regiões do Brasil</strong>
                            podem trocar utilizando o site.&nbsp; Como ?&nbsp;
                        </p>
                        <div>
                            - Pelos Correios (que atendem todo o Brasil)
                        </div>
                        <div>
                            - Pessoalmente, em qualquer lugar pr&eacute;-combinado entre as partes *
                        </div>
                        <div>
                            - Ou atrav&eacute;s dos Pontos de Trocas ....
                        </div>
                        <br/>
                        <div>
                            <span
                                style="font-size: 15px; letter-spacing: -1px; text-align: center; word-spacing: 2px;">(*) O Quintal sugere que a troca seja combinada num lugar movimentado (como museus, bibliotecas, etc.)</span>
                        </div>
                        <h2>
                            Ainda n&atilde;o tem Ponto de Trocas perto de voc&ecirc;?
                        </h2>
                        <p>
                            N&atilde;o se preocupe, voc&ecirc;
                            pode trocar normalmente combinando em algum lugar de sua prefer&ecirc;ncia ou pelos
                            correios. O Quintal de Trocas &eacute;
                            para todo o Brasil e muitas pessoas j&aacute; trocaram, mesmo n&atilde;o tendo um Ponto de
                            Trocas por perto. De qualquer forma, estamos trabalhando intensamente para ampliar a lista
                            e, a cada dia, mais e mais parceiros se juntam ao Quintal.
                        </p>
                        <h2>
                            Quer ser um Ponto de Trocas?
                        </h2>
                        <p>
                            Como a proposta do Quintal &eacute; criar uma alian&ccedil;a cada vez mais forte, foi
                            desenvolvido um processo simples e espec&iacute;fico para o cadastro e tratamento das
                            empresas e institui&ccedil;&otilde;es que tamb&eacute;m querem ser um Ponto de Trocas.
                        </p>
                        <p>
                            <strong>Basta preencher e enviar o formul&aacute;rio deste endere&ccedil;o</strong>: <a
                                href="http://goo.gl/h1Y4Mi" target=_blank>http://goo.gl/h1Y4Mi</a>
                        </p>
                        <p>
                            Espalhados por diferentes cidades Brasil afora, estes pontos s&atilde;o verdadeiros alidados
                            pela inf&acirc;ncia. &Eacute;
                            importante ressaltar que o Ponto de Trocas n&atilde;o precisa fazer nada al&eacute;m de se
                            posicionar como um ponto de encontro para as crian&ccedil;as e respons&aacute;veis, simples
                            assim!
                        </p>
                        <p>Se voc&ecirc; n&atilde;o &eacute; um Ponto de Trocas, mas conhece algum lugar bem bacana na
                            sua cidade, mande um email para n&oacute;s ou fale com o espa&ccedil;o da sua cidade sobre o
                            Quintal.
                        </p>
                        <p>
                            Seja voc&ecirc; um agente transformador:
                            &nbsp;<strong>contato@quintaldetrocas.com.br</strong>
                        </p>
                        <h2>
                            O que ganhamos?
                        </h2>
                        <div class="add-info">
                            <ul class="list">
                                <li style="width: 300px;">
                                    <div class="in">
                                        <div class="img">
                                            <img alt="image description"
                                                 src="http://www.quintaldetrocas.com.br/img/img-49.png"/>
                                        </div>
                                        <span class="name">Ponto de Trocas</span>
                                        <p>
                                            Visitas &agrave; loja por p&uacute;blico segmentado. A pessoa que ir&aacute;
                                            trocar
                                            conhecer&aacute;
                                            um estabelecimento preocupado com a sustentabilidade.<br/>
                                            Nota: S&oacute; empresas realmente preocupadas com a sustentabilidade
                                            poder&atilde;o
                                            ser
                                            pontos de troca.
                                        </p>
                                    </div>
                                </li>
                                <li style="width: 300px;">
                                    <div class="in">
                                        <div class="img">
                                            <img alt="image description"
                                                 src="http://www.quintaldetrocas.com.br/img/img-50.png"/>
                                        </div>
                                        <span class="name">Sociedade</span>
                                        <p>
                                            Possibilita que mais crian&ccedil;as troquem os seus brinquedos, livros
                                            e fantasias
                                            sem
                                            gasto algum! Al&eacute;m disso, ainda fazem novos amigos e ganham mais
                                            tempo para a
                                            brincadeira.
                                        </p>
                                    </div>
                                </li>
                                <li style="width: 300px;">
                                    <div class="in">
                                        <div class="img">
                                            <img alt="image description"
                                                 src="http://www.quintaldetrocas.com.br/img/img-51.png"/>
                                        </div>
                                        <span class="name">Quintal de Trocas</span>
                                        <p>
                                            Um ambiente seguro e agrad&aacute;vel para as pessoas na hora da
                                            troca.
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
    <!-- /exchange-area -->
</div>
<div class="w1">