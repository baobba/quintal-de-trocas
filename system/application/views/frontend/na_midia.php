<div class="page-title">
    <div class="in">
        <h1>Na mídia</h1>
        <ul class="breadcrumbs">
            <li><a href="<?php echo $base_url; ?>">Home</a></li>
            <li>Na mídia</li>
        </ul>
    </div>
</div>
<!-- /page-title -->
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
<!-- /media-area -->