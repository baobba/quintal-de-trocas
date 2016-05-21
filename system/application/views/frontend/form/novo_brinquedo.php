<form action="<?php echo $base_url . URL_USUARIO_MEUS_DADOS; ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo $formxNewToy->get_form_name(); ?>" value="<?php echo $formxNewToy->use_field($formxNewToy->get_form_name())->get_value(); ?>" />
    <fieldset>
        <h2>Preencha o formulário abaixo para cadastrar seu brinquedo:</h2>
        <div class="form-in">
            <h3 class="ttl">Comece com o upload de algumas fotos do seu
                brinquedo:</h3>
            <div class="file-block">
                <input type="file" name="image_main" title="Selecione a foto">
            </div>

            <div class="file-block">
                <input type="file" name="image_extra1" title="Selecione a foto">
            </div>

            <div class="file-block">
                <input type="file" name="image_extra2" title="Selecione a foto">
            </div>

            <div class="row">
                <label class="lbl" for="lbl-19">Título do brinquedo</label>
                <div class="text">
                    <input type="text" id="lbl-19" name="name" value="<?php echo $formxNewToy->use_field('name')->get_posted(); ?>"/>
                </div>
            </div>

            <div class="row">
                <label class="lbl" for="lbl-21">Estado</label>
                <?php
                $toyStateSelected = $formxNewToy->use_field('toy_state')->get_posted();
                $toyStateName = $toyStateSelected && array_key_exists($toyStateSelected, $toyStates) ? $toyStates[$toyStateSelected] : 'Selecione...';
                ?>
                <select id="state_combo" class="cs-3" title="<?php echo $toyStateName; ?>" name="toy_state">
                    <option value="">Selecione</option>
                    <?php
                    foreach ($toyStates as $toyStateId => $toyState) {
                        $selected = $toyStateSelected == $toyStateId ? 'selected="selected"' : '';
                        echo sprintf('<option value="%s" %s>%s</option>', $toyStateId, $selected, $toyState);
                    }
                    ?>
                </select>
            </div>

            <div class="row">
                <label class="lbl" for="lbl-21">Cidade</label>
                <select id="city_combo" class="cs-3" title="<?php echo $toyStateName; ?>" name="toy_city" disabled="disabled">
                    <option value="">Selecione</option>
                </select>
            </div>

            <div class="row">
                <label class="lbl" for="lbl-20">Descrição do brinquedo</label>
                <div class="text">
                    <textarea id="lbl-20" cols="30" rows="10" name="description" style="resize: none;"><?php echo $formxNewToy->use_field('description')->get_posted(); ?></textarea>
                </div>
            </div>

            <div class="row">
                <label class="lbl" for="lbl-21">Marca do brinquedo</label>
                <?php
                $toyBrandSelected = $formxNewToy->use_field('toy_brand')->get_posted();
                $toyBrandName = $toyBrandSelected && array_key_exists($toyBrandSelected, $toyBrands) ? $toyBrands[$toyBrandSelected] : 'Selecione...';
                ?>
                <select id="lbl-23" class="cs-3" title="<?php echo $toyBrandName; ?>" name="toy_brand">
                    <?php
                    foreach ($toyBrands as $toyBrandId => $toyBrand) {
                        $selected = $toyBrandSelected == $toyBrandId ? 'selected="selected"' : '';
                        echo sprintf('<option value="%s" %s>%s</option>', $toyBrandId, $selected, $toyBrand);
                    }
                    ?>
                </select>
            </div>

            <div class="row">
                <label class="lbl" for="lbl-21">Faixa etária</label>
                <?php
                $toyAgeSelected = $formxNewToy->use_field('toy_age')->get_posted();
                $toyAgeName = $toyAgeSelected && array_key_exists($toyAgeSelected, $toyAges) ? $toyAges[$toyAgeSelected] : 'Selecione...';
                ?>
                <select id="lbl-23" class="cs-3" title="<?php echo $toyAgeName; ?>" name="toy_age">
                    <?php
                    foreach ($toyAges as $toyAgeId => $toyAge) {
                        $selected = $toyAgeSelected == $toyAgeId ? 'selected="selected"' : '';
                        echo sprintf('<option value="%s" %s>%s</option>', $toyAgeId, $selected, $toyAge);
                    }
                    ?>
                </select>
            </div>

            <div class="row">
                <label class="lbl" for="lbl-21">Tipo de Brinquedo</label>
                <?php
                $toyCategorySelected = $formxNewToy->use_field('toy_category')->get_posted();
                $toyCategoryName = $toyCategorySelected && array_key_exists($toyCategorySelected, $toyCategories) ? $toyCategories[$toyCategorySelected] : 'Selecione...';
                ?>
                <select id="lbl-23" class="cs-3" title="<?php echo $toyCategoryName; ?>" name="toy_category">
                    <?php
                    foreach ($toyCategories as $toyCategoryId => $toyCategory) {
                        $selected = $toyCategorySelected == $toyCategoryId ? 'selected="selected"' : '';
                        echo sprintf('<option value="%s" %s>%s</option>', $toyCategoryId, $selected, $toyCategory);
                    }
                    ?>
                </select>
            </div>

            <div class="row">
                <label class="lbl" for="lbl-22">Peso do brinquedo</label>
                <div class="text">
                    <input type="text" id="lbl-22" name="weight" value="<?php echo $formxNewToy->use_field('weight')->get_posted(); ?>" />
                </div>
            </div>
        </div>

        <div class="row">
            <h3 class="ttl">Interesses</h3>
        </div>

        <div class="row">
            <label class="lbl" for="lbl-21">Marca do Brinquedo</label>
            <div class="text" style="overflow:visible">
                <?php
                $toyBrandInterestSelected = $formxNewToy->use_field('toy_brand_interest')->get_posted();
                ?>
                <select class="chosen-select" data-placeholder="Selecione..." name="toy_brand_interest[]" multiple="multiple" style="background-image:none;background-color:#F0EBE1">
                    <?php
                    foreach ($toyBrands as $toyBrandId => $toyBrand) {
                        $selected = in_array($toyBrandId, $toyBrandInterestSelected) ? 'selected="selected"' : '';
                        echo sprintf('<option value="%s" %s>%s</option>', $toyBrandId, $selected, $toyBrand);
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row">

            <label class="lbl" for="lbl-21">Faixa etária</label>
            <div class="text" style="overflow:visible">
                <?php
                $toyAgeInterestSelected = $formxNewToy->use_field('toy_age_interest')->get_posted();
                ?>
                <select class="cs-3 chosen-select" data-placeholder="Selecione..." name="toy_age_interest[]" multiple="multiple" style="background-image:none;background-color:#F0EBE1">
                    <?php
                    foreach ($toyAges as $toyAgeId => $toyAge) {
                        $selected = in_array($toyAgeId, $toyAgeInterestSelected) ? 'selected="selected"' : '';
                        echo sprintf('<option value="%s" %s>%s</option>', $toyAgeId, $selected, $toyAge);
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row">
            <label class="lbl" for="lbl-21">Tipo de Brinquedo</label>
            <div class="text" style="overflow:visible">
                <?php
                $toyCategoryInterestSelected = $formxNewToy->use_field('toy_category_interest')->get_posted();
                ?>
                <select class="cs-3 chosen-select" data-placeholder="Selecione..." name="toy_category_interest[]" multiple="multiple" style="background-image:none;background-color:#F0EBE1">
                    <?php
                    foreach ($toyCategories as $toyCategoryId => $toyCategory) {
                        $selected = in_array($toyCategoryId, $toyCategoryInterestSelected) ? 'selected="selected"' : '';
                        echo sprintf('<option value="%s" %s>%s</option>', $toyCategoryId, $selected, $toyCategory);
                    }
                    ?>
                </select>
            </div>		
        </div>

        <p>
            Ao clicar em enviar você está de acordo com a <a href="<?php echo $base_url ?>home/politica" class="iframe-content">política
                de troca</a> do Quintal de Trocas.
        </p>
        <div class="btn-holder">
            <input class="btn-submit" type="submit" value="Cadastrar brinquedo" />
        </div>
    </fieldset>
</form>
