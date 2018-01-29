import {AngularCompilerPlugin} from '@ngtools/webpack';
import {Configuration} from 'webpack';
import {WebpackArgs} from './webpack-args';
import {getCommonConfig} from './webpack.common';
const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

export const webpackConfig = (args: WebpackArgs): Configuration => {
    const jsPath = path.join(__dirname, '../public/js');

    const devConfig: Configuration = {
        devServer: {
            historyApiFallback: true,
            watchOptions: {
                aggregateTimeout: 300,
                poll: 1000,
            },
        },
        devtool: 'source-map',
        module: {
            rules: [
                {
                    test: /\/public\/js\/.+\.ts$/,
                    enforce: 'pre',
                    use: {
                        loader: 'tslint-loader',
                        options: {configFile: path.join(jsPath, 'tslint', 'main.json')},
                    },
                }, {
                    test: /\.ts$/,
                    loader: '@ngtools/webpack',
                }, {
                    // transpile sass to css and load it inline
                    test: /\.scss$/,
                    loader: ExtractTextPlugin.extract({ // TODO remove this line for HMR
                        use: [
                            // {loader: "style-loader"}, TODO add this line for HMR
                            {
                                loader: 'css-loader',
                                options: {sourceMap: true},
                            },
                            {
                                loader: 'sass-loader',
                                options: {sourceMap: true},
                            },
                        ],
                    }),
                },
            ],
        },
        plugins: [
            new AngularCompilerPlugin({
                tsConfigPath: path.join(jsPath, 'tsconfig.json'),
                entryModule: path.join(jsPath, 'app', 'app.module#AppModule'),
            }),

            // live chunk replacement via webpack's dev-server
            //  new webpack.HotModuleReplacementPlugin(), TODO HMR

            // generate separate css file to load see to do above, based on output.path
            new ExtractTextPlugin('css/main.css'),

            // @see https://github.com/angular/angular/issues/11580
            new webpack.ContextReplacementPlugin(
                /(.+)?angular(\\|\/)core(.+)?/,
                path.resolve(__dirname, '../src'),
            ),
        ],
    };

    return merge(getCommonConfig(args), devConfig);
};
