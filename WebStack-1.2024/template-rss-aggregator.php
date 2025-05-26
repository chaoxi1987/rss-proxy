<?php
/*
Template Name: RSS 新闻聚合页面
*/
get_header(); ?>

<div class="rss-container">
    <h2>最新新闻</h2>
    <div class="rss-feed-list">
        <?php
        // 定义 RSS 源数组
        $feeds = [
            'https://example.com/feed', // 替换为实际的 RSS 地址
            'https://anothernews.com/rss',
        ];

        foreach ($feeds as $feed_url) {
            $rss = fetch_feed($feed_url);
            if (!is_wp_error($rss)) {
                $maxitems = $rss->get_item_quantity(5);
                $rss_items = $rss->get_items(0, $maxitems);

                foreach ($rss_items as $item) {
                    echo '<div class="rss-item">
                            <h3><a href="' . esc_url($item->get_permalink()) . '" target="_blank">' . esc_html($item->get_title()) . '</a></h3>
                            <p>' . wp_trim_words(esc_html($item->get_description()), 20) . '</p>
                        </div>';
                }
            } else {
                echo '<p>无法加载 RSS 源: ' . esc_url($feed_url) . '</p>';
            }
        }
        ?>
    </div>
</div>

<?php get_footer();