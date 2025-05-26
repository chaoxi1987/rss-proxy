const fs = require('fs');
const path = require('path');
const axios = require('axios');
const parser = require('fast-xml-parser');

// 读取 feeds.json
const feeds = JSON.parse(fs.readFileSync('feeds.json', 'utf8'));

// 存储结果
const results = {};

// 抓取每个 RSS 源
feeds.forEach(async (feed) => {
    try {
        const response = await axios.get(feed.url);
        if (parser.validate(response.data)) {
            const xmlData = parser.parse(response.data);
            results[feed.name] = xmlData.rss.channel.item || [];
            console.log(`✅ 成功抓取: ${feed.name}`);
        } else {
            console.error(`❌ 无效的 XML 格式: ${feed.name}`);
        }
    } catch (error) {
        console.error(`⚠️ 抓取失败: ${feed.name}`, error.message);
    }
});

// 写入 output/rss.json
setTimeout(() => {
    fs.writeFileSync(path.join('output', 'rss.json'), JSON.stringify(results, null, 2), 'utf8');
    console.log('📄 RSS 数据已写入 output/rss.json');
}, 5000); // 等待所有请求完成