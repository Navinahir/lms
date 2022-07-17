<?php
if (!empty($records)) {
    foreach ($records as $value) {
        ?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="doc-wrap blog-detail-wrap">
                <div class="thumbnail">
                    <?php
                    $default_image = site_url('assets/blogs/img/image-preview-blog-detail.png');

                    $filename = './uploads/blogs/' . $value['blog_no'] . '/' . $value['file'];
                    if (!empty($value['file']) && file_exists($filename)) {
                        $default_image = site_url('/uploads/blogs/' . $value['blog_no'] . '/' . $value['file']);
                    }
                    ?>
                    <img src="<?= $default_image ?>" alt="image">
                </div>
                <div class="blog-detail">
                    <div class="blog-date"><em><?= date('dS F Y', strtotime($value['created_date'])) ?></em></div>
                    <div class="title">
                        <a  href="<?= site_url('blogs/' . base64_encode($value['id'])) ?>">
                            <h2><?= $value['blog_title'] ?></h2>
                        </a>
                    </div>
                    <div class="blog-tag">
                        <?php
                        $tagsArr = explode(',', $value['tags']);

                        foreach ($tagsArr as $tag) {
                            ?>
                            <span class="w3-tag w3-blue"><?= $tag ?></span>
                        <?php } ?>
                    </div>
                    <div class="blog-meta">
                        <?= $value['blog_content'] ?>
                    </div>
                    <a class="read-more-anchor" href="<?= site_url('blogs/' . base64_encode($value['id'])) ?>">Read More... </a>
                </div>
            </div>
        </div>
        <?php
    }
} ?>
