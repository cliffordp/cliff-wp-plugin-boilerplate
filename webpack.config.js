'use strict';

const wpDefaults = require( '@wordpress/scripts/config/webpack.config' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

module.exports = {
	...wpDefaults,
	plugins: [
		...wpDefaults.plugins,
		new MiniCssExtractPlugin(),
	],
	module: {
		...wpDefaults.module,
		rules: [
			...wpDefaults.module.rules,
			{
				test: /\.jsx?/,
				use: [ 'import-glob' ],
			},
			{
				test: /\.p?css/,
				use: [
					'import-glob',
					{
						loader: MiniCssExtractPlugin.loader,
						options: {
							hmr: process.env.NODE_ENV !== 'development',
						},
					},
					'css-loader',
					'postcss-loader',
				],
			},
		],
	},
};