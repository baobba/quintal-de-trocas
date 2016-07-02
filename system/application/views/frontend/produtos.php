<div class="page-title page-title2">
    <div class="in">
        <h1>Quero trocar</h1>
        <ul class="breadcrumbs">
            <li><a href="<?php echo $base_url; ?>">Home</a></li>
            <li>Quero trocar</li>
        </ul>
    </div>
</div><!-- /page-title -->
<article class="main main-inverse">
    <div class="content">
        <div class="item-filter">
            <div class="in">
                <div class="form">
                    <form action="<?php echo $url ?>" method="post">
                        <fieldset>
                            <label for="lbl-33">Ordenar a lista:</label>
                            <select id="lbl-33" class="cs-2" title="Selecione..." onchange="this.form.submit()"
                                    name="order_by">
                                <option value="0">Crescente</option>
                                <option value="1">Descrescente</option>
                            </select>
                        </fieldset>
                    </form>
                </div>
                <h2><span class="tbl"><span class="tbl-cell"><span
                                class="tbl-in">Brinquedos disponíveis para troca</span></span></span></h2>
            </div>
        </div>
        <ul class="item-list">
            <?php echo $toyList; ?>
        </ul>
        <script>
            $(function () {
                $('.item-list').jscroll({nextSelector: 'a.next'});
            })
        </script>
        <?php
        if ($toys->num_rows == 0) {
            echo '<h4>Nada encontrado</h4>';
        } else {
            ?>
            <ul class="paging">
                <?php
                $pages = $pagination->generate();

                foreach ($pages as $k => $item) {
                    if ($k == 'current' | $item == '') {
                        continue;
                    }

                    if ($k == 'pages') {
                        $only = count($item) == 1 ? 'only' : '';

                        foreach ($item as $page) {
                            $active = $page == $pages['current'] ? 'active' : '';
                            echo sprintf('<li class="%s"><a href="%s" class="link ie-fix">%s</a></li>', $active, $pagination->get_url($page), $page);
                        }
                    } elseif ($k == 'prev') {
                        echo sprintf('<li class="prev"><a href="%s">Anterior</a></li>', $pagination->get_url($item));
                    } elseif ($k == 'next') {
                        echo sprintf('<li class="next"><a href="%s">Pr&oacute;ximo</a></li>', $pagination->get_url($item));
                    }
                }
                ?>
            </ul>
            <?php
        } ?>
    </div><!-- /content -->
    <aside>
        <div class="filter-list">
            <form action="<?php echo $base_url . URL_PRODUTOS ?>" method="post">
                <input type="hidden" name="toy_name" value="<?php echo $toyName; ?>"/>
                <fieldset>
                    <h3>Filtros:</h3>

                    <h4>Por estado</h4>
                    <select name="toy_state[]" onchange="this.form.submit()">
                        <option value="">Selecione</option>
                        <?php
                        foreach ($toyStates as $toyStateId => $toyState) {
                            $selected = in_array($toyStateId, $toyStateSelected) ? 'selected="selected"' : '';
                            echo sprintf('<option value="%s" %s>%s</option>', $toyStateId, $selected, $toyState);
                        }
                        ?>
                    </select>

                    <h4>Por cidade</h4>

                    <select name="toy_city[]" onchange="this.form.submit()">
                        <option value="">Selecione</option>
                        <?php
                        foreach ($toyCities as $toyCityId => $toyCity) {
                            $selected = in_array($toyCityId, $toyCitySelected) ? 'selected="selected"' : '';
                            echo sprintf('<option value="%s" %s>%s</option>', $toyCityId, $selected, $toyCity);
                        }
                        ?>
                    </select>

                    <h4>Por idade</h4>
                    <ul class="list">
                        <?php
                        foreach ($toyAges as $toyAgeId => $toyAge) {
                            $selected = in_array($toyAgeId, $toyAgeSelected) ? 'checked="checked"' : '';
                            echo sprintf('
                                    <li>
                                        <input id="lbl-2%d" name="toy_age[]" class="chk" type="checkbox" value="%d" %s onchange="this.form.submit()"/>
							            <label for="lbl-2%d">%s</label>
					                </li>', $toyAgeId, $toyAgeId, $selected, $toyAgeId, $toyAge);
                        }
                        ?>
                    </ul>

                    <h4>Por tipo de brinquedo</h4>
                    <ul class="list">
                        <?php
                        foreach ($toyCategories as $toyCategoryId => $toyCategory) {
                            $selected = in_array($toyCategoryId, $toyCategorySelected) ? 'checked="checked"' : '';
                            echo sprintf('
                                    <li>
                                        <input id="lbl-3%d" name="toy_category[]" class="chk" type="checkbox" value="%d" %s onchange="this.form.submit()"/>
							            <label for="lbl-3%d">%s</label>
					                </li>', $toyCategoryId, $toyCategoryId, $selected, $toyCategoryId, $toyCategory);
                        }
                        ?>
                    </ul>

                    <h4>Por marca</h4>
                    <ul class="list">
                        <?php
                        foreach ($toyBrands as $toyBrandId => $toyBrand) {
                            $selected = in_array($toyBrandId, $toyBrandSelected) ? 'checked="checked"' : '';
                            echo sprintf('
                                    <li>
                                        <input id="lbl-4%d" name="toy_brand[]" class="chk" type="checkbox" value="%d" %s onchange="this.form.submit()"/>
							            <label for="lbl-4%d">%s</label>
					                </li>', $toyBrandId, $toyBrandId, $selected, $toyBrandId, $toyBrand);
                        }
                        ?>
                    </ul>
                </fieldset>
            </form>
        </div>
    </aside>
</article><!-- /main -->

<div class="fixed">
    <a href="http://www.quintaldetrocas.com.br/usuario/login/" class="btn">Comece agora</a>
</div>