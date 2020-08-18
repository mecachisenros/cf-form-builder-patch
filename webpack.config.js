const defaultConfig = require('@wordpress/scripts/config/webpack.config')
const path = require('path');

// override entry script
module.exports = {
  ...defaultConfig,
  entry: {
		index: path.resolve(process.cwd(),  path.join('src'), 'patchFormBuilder.js'),
	}
}
