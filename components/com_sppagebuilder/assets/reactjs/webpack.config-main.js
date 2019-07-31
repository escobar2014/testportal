'use strict';

var path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var BrowserSyncPlugin = require('browser-sync-webpack-plugin');

module.exports = {
	entry: [
	path.join(__dirname, 'src/main.js')
	],
	output: {
		path: path.join(__dirname, 'public/build/'),
		filename: 'bundle.js'
	},
	plugins: [
		// output a separate css bundle
		new ExtractTextPlugin('bundle.css'),

		// reloads browser when the watched files change
		new BrowserSyncPlugin({
			// use existing Apache virtual host
			proxy: 'http://localhost/',
			tunnel: false,
			// watch the built files and the index file
			files: ['public/build/*', 'public/index.php']
		}),

		new webpack.DefinePlugin({
			__DEV__: false,
			'process.env': {
				NODE_ENV: JSON.stringify('production')
			}
		})
		],
		module: {
			loaders: [
			// transpiles JSX and ES6
			{ test: /\.js$/, exclude: /node_modules/, loader: 'babel' },
			]
		},
	// needed to make request-promise work
	node: {
		net: 'empty',
		tls: 'empty'
	}
};
