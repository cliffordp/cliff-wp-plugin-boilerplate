module.exports = ({ file, options, env }) => ({
	plugins: {
		'postcss-nested': {},
		'postcss-modules': {},
		'cssnano': {
			preset: [
				'default',
				{
					discardComments: {
						removeAll: true,
					},
				},
			],
		},
	},
});