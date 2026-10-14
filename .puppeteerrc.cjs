const { join } = require('path');

// Keep the PDF browser inside the project so the PHP user (not only the deploy user) can run it.
module.exports = {
    cacheDirectory: join(__dirname, '.cache', 'puppeteer'),
};
