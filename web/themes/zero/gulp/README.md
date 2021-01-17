# Install

```shell
- cd web/themes/<theme> # switch directory to theme root
- npm init
- git clone https://github.com/loomgmbh/node-gloom-base.git gulp
- npm install gulp gulp-rename gulp-twig gulp-uglify gulp-sass gulp-changed glob
- npm install https://github.com/loomgmbh/node-gloom-plugin.git
- cp gulp/gulpfile.example.js ./gulpfile.js
- rm -rf gulp/.git # Remove .git files within /gulp to work on project enviroment
```

# Install npm plugin

- Install the module with npm
- Add in `config.json` the key `"loadTasks": ["<npm package name>"]`

# Compile
- `gulp styles`
- `gulp scripts`

# Watch
- `gulp watch`

# Full compile 
- `gulp`
