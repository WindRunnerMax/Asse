module.exports = {
	devServer: {
		proxy: {
			'/': {
				target: 'http://dev.touchczy.top',
				 ws: true,
				changeOrigin: true,
				pathRewrite: {}
			}
		}
	}
}
