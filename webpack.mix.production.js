const merge= require('webpack-merge');
let config= {};


//* 配置打包分析器
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');
config= merge(config, {
    plugins: [
        new BundleAnalyzerPlugin({ analyzerPort: 8919 })
    ],
});
//*/


//* 生成 .gz 文件
const CompressionWebpackPlugin = require('compression-webpack-plugin');
config= merge(config, {
    plugins: [
        new CompressionWebpackPlugin({
            // filename: '[path].gz[query]',
            // algorithm: 'gzip',
            // test: new RegExp('\\.(js|css)$'),
            // threshold: 10240,
            // minRatio: 0.8,
            // deleteOriginalAssets: true,
        })
    ],
});
//*/


module.exports= config;
