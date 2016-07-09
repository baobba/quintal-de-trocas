</div><!-- class 'w1' -->
<div class="page-title">
    <div class="in">
        <h2>Na Mídia | </h2><span>onde já aparecemos</span>
    </div>
</div>
<div class="w1">
    <div id="container-carousel-na-midia" class="container">
        <div class="carousel">
            <?php
            $pos = 1;
            foreach ($press->result() as $_press) {
                ?>
                <div class="slider-carousel">
                    <?php $baselink = $base_url . 'img/slider' . $pos . '.png'; ?>
                    <div class='img' style='background-image: url(<?php echo $baselink; ?>)'></div>
                </div>
                <?php
                if ($pos == 5) {
                    break;
                } else {
                    $pos++;
                }
            } ?>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.carousel').slick({
                autoplay: true,
                dots: true,
                prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Anterior" tabindex="0" role="button">Anterior</button>',
                nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Próximo" tabindex="0" role="button">Próximo</button>',
            });
        });
    </script>
    <article class="namidia-area">
        <div class="container">
            <?php foreach ($press->result() as $_press): ?>
                <div class="box">
                    <a href="<?php echo $_press->url; ?>" title="<?php echo $_press->name; ?>" target="_blank">
                        <?php $baselink = $base_url . URL_UPLOAD_IMAGE . $_press->cover_image; ?>
                        <?php echo "<div class='img' style='background-image: url({$baselink}); '></div>"; ?>
                        <h3>
                            <?php echo (strlen($_press->name) > 25) ? substr($_press->name, 0, 25) . "..." : $_press->name; ?>
                        </h3>
                        <h4>
                            <?php echo $_press->category_name; ?>
                        </h4>
                        <h5>
                            <?php echo date_create_from_format('Y-m-d H:i:s', $_press->publicated_at)->format('d/m/Y'); ?>
                        </h5>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </article>
</div>
<div class="w1">