# Farm Garden Game (农场花园种花偷花)

A full-stack project for a "Farm Garden" game where players can plant, harvest, and "steal" flowers/fruits.

## Project Overview

- **Frontend**: Developed with **uni-app** (Vue 3). Supports multiple platforms but currently optimized for **WeChat Mini Program** (as per README).
- **Backend**: Built with **Pure PHP** (no framework).
- **Database**: **MySQL**.
- **Key Features**: Planting, shop for seeds/items, leaderboard, social features (stealing flowers), and daily check-ins.

## Directory Structure

- `common/`: Utility functions, including `server.js` for API requests.
- `components/`: Custom UI components like `ezguide`, `ezlogin`, `ezwindow`, and `posters-layer`.
- `pages/`: Main application pages (`index`, `login`, `webview`).
- `static/`: Project assets (images, sounds, icons).
- `styles/`: Global CSS styles.
- `▓┐╩≡╦∙╨Φ╬─╝■/`: Project-related documents and backend source code (Folder name may appear garbled due to encoding).
  - `php-api/`: PHP backend source code.
  - `db.sql`, `amazpot_seeds.sql`, `amazpot_shop.sql`: Database schema and initial data.
  - `cdn-images/`: Image assets intended for CDN hosting.

## Development & Deployment

### Frontend (uni-app)
1.  **Requirement**: [HBuilderX](https://www.dcloud.io/hbuilderx.html).
2.  **Configuration**: Modify `common/server.js` to set your server's `HOST`, `API_HOST`, and `CDN_HOST`.
3.  **Run/Build**: Open the project in HBuilderX and use the "Run" or "Publish" menus for your target platform.

### Backend (PHP)
1.  **Requirement**: A web server with PHP support (e.g., Apache/Nginx + PHP 5.6+).
2.  **Deployment**: Upload the contents of `▓┐╩≡╦∙╨Φ╬─╝■/php-api/` to your server.
3.  **Permissions**: Ensure `access_token.json` is readable and writable by the server process.
4.  **Database Configuration**:
    - Import the `.sql` files found in `▓┐╩≡╦∙╨Φ╬─╝■/` into your MySQL database.
    - Edit `php-api/mysql.php` with your database credentials (`host`, `user`, `pass`, etc.).

### Database
- `db.sql`: Contains the table structure.
- `amazpot_seeds.sql`: Contains the seeds/plants data.
- `amazpot_shop.sql`: Contains the shop items data.

## Development Conventions

- **API Communication**: The frontend uses `uni.request` wrapped in `common/server.js`. Standard success code is `err == 0`.
- **Backend Style**: Procedural PHP using the `mysql_connect` extension (Note: This is deprecated in newer PHP versions; consider updating to `mysqli` or `PDO` if compatibility issues arise).
- **State Management**: Uses PHP sessions (`PHPSESSID`) passed via headers in `uni.request`.
