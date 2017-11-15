Set your machine (only once):
1. Download node.js https://nodejs.org/en/download/
2. Run command prompt
3. Install gulp globally - "npm install gulp -g"
4. Install rimraf globally - "npm install rimraf -g"

Set your project:
1. Run command prompt withing your project (theme) folder
2. Install node_modules - "npm install"
3. Build files - "gulp build"
4. Generate fonticons - "gulp iconfont"
5. Start gulp watch - "gulp watch"
* you cannot run another task while in watch mode
* to stop gulp watch - press ctrl+c, y, enter

Remove node_modules from your project (theme) folder
1. Delete node_modules folder - "rimraf node_modules"
* always delete node_modules before deploying site to a live server
