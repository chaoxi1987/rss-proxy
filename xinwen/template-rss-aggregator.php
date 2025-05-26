<?php
/*
Template Name: RSS 新闻聚合页面
*/
get_header(); ?>

<div class="rss-container">
    <h2>最新新闻</h2>
    <div class="rss-feed-list" id="rss-feed-list">
        <p>正在加载新闻...</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rssJsonUrl = 'https://chaoxi1987.github.io/rss-proxy/rss.json';

    fetch(rssJsonUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('网络响应失败');
            }
            return response.json();
        })
        .then(data => {
            const container = document.getElementById('rss-feed-list');
            container.innerHTML = '';

            let hasItems = false;

            for (const source in data) {
                const items = data[source];
                if (Array.isArray(items) && items.length > 0) {
                    hasItems = true;
                    items.slice(0, 5).forEach(item => {
                        const newsItem = document.createElement('div');
                        newsItem.className = 'rss-item';
                        newsItem.innerHTML = `
                            <h3><a href="${item.link || item.guid}" target="_blank">${item.title}</a></h3>
                            <p>${item.description || item.contentSnippet || '暂无摘要'}</p>
                        `;
                        container.appendChild(newsItem);
                    });
                }
            }

            if (!hasItems) {
                container.innerHTML = '<p>暂时没有可用新闻。</p>';
            }
        })
        .catch(error => {
            console.error('获取新闻失败:', error);
            document.getElementById('rss-feed-list').innerHTML = '<p>无法加载新闻，请稍后再试。</p>';
        });
});
</script>

<?php get_footer();